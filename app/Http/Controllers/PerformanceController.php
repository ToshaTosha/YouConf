<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Performance;
use App\Models\Chat;
use App\Models\File;
use App\Models\Section;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\PerformanceStatusChanged;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::user()->id);
        $statuses = Status::all();

        if ($user && $user->hasRole('participant')) {
            // Если пользователь участник, показываем только его заявки
            $performances = Performance::where('user_id', $user->id)
                ->with(['files', 'section', 'status'])
                ->get();
        } else {
            // Если пользователь эксперт или администратор, показываем все заявки
            $performances = Performance::with(['files', 'section', 'user', 'status'])
                ->get();
        }

        return inertia('Performances/Index', [
            'performances' => $performances,
            'statuses' => $statuses,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        if (!$user->hasRole('expert')) {
            return response()->json(['message' => 'Доступ запрещен.'], 403);
        }

        // Получаем заявку со связанными данными
        $performance = Performance::with(['status', 'user'])->findOrFail($id);

        // Сохраняем старый статус перед изменением
        $oldStatus = $performance->status;

        // Обновляем статус
        $performance->status_id = $request->status_id;
        $performance->save();

        // Получаем новый статус после обновления
        $newStatus = $performance->fresh()->status;
        $performance->user->notify(
            new PerformanceStatusChanged($performance, $oldStatus, $newStatus)
        );
        $recipient = $performance->user;

        $notificationCheck = DB::table('notifications')
            ->where('notifiable_id', $recipient->id)
            ->where('notifiable_type', get_class($recipient))
            ->orderBy('created_at', 'desc')
            ->first();

        // Проверяем, что статус действительно изменился
        if ($oldStatus->id !== $newStatus->id) {
            // Отправляем уведомление (передаём объекты Performance и Status)

            return redirect()->back()->with('success', [
                'title' => 'Статус изменен',
                'message' => "Статус выступления '{$performance->title}' изменен на '{$newStatus}'",
                'performance_id' => $performance->id
            ]);
        }

        return redirect()->back()->with('success', 'Статус заявки успешно обновлён');
    }

    public function apply(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'section_id' => 'required|exists:sections,id',
        //     'files.*' => 'file|mimes:jpg,png,pdf|max:2048',
        // ]);

        DB::transaction(function () use ($request) {
            // Создаем новую заявку
            $performance = Performance::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => Auth::id(),
                'status_id' => 1, // Статус "на рассмотрении"
                'section_id' => $request->section_id,
            ]);

            // Создаем чат для заявки
            Chat::create([
                'chatable_type' => Performance::class, // Указываем тип модели
                'chatable_id' => $performance->id,
            ]);

            // Загружаем файлы, если они есть
            if ($request->hasFile('files')) {
                $this->storeFiles($request->file('files'), $performance); // Сохраняем файлы, связанные с Performance 
            }
        });

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
    }

    public function update(Request $request, $id)
    {
        $performance = Performance::findOrFail($id);
        if (in_array($performance->status_id, [2, 4])) {
            return response()->json(['error' => 'Редактирование выступления невозможно, так как статус не позволяет это.'], 403);
        }
        DB::transaction(function () use ($request, $performance) {

            // Обновляем основную заявку
            $performance->update($request->only(['title', 'description', 'status_id']));

            // Загружаем новые файлы, если они есть
            $files = $request->input('files'); // Получаем массив файлов (существующие файлы)
            $newFiles = $request->file('files'); // Получаем новые файлы

            // Обрабатываем существующие файлы
            if ($files) {
                foreach ($files as $file) {
                    $fileData = json_decode($file, true); // Декодируем JSON
                    if (is_array($fileData)) {
                        // Это существующий файл, обновляем его связь
                        $existingFile = File::find($fileData['id']);
                        if ($existingFile) {
                            $existingFile->update([
                                'fileable_id' => $performance->id,
                                'fileable_type' => get_class($performance),
                            ]);
                        }
                    }
                }
            }

            // Обрабатываем новые файлы
            if ($newFiles) {
                foreach ($newFiles as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        // Это новый файл, загружаем его
                        $this->storeFiles([$file], $performance);
                    }
                }
            }
        });

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
    }

    public function show($id)
    {
        $performance = Performance::with(['files', 'section', 'user', 'status', 'chat.messages.user'])->findOrFail($id);

        // Получаем сообщения из чата
        $chat = $performance->chat; // Получаем чат, связанный с заявкой
        $messages = $chat ? $chat->messages : []; // Получаем сообщения, если чат существует

        return inertia('Performances/Show', [
            'performance' => $performance,
            'messages' => $messages, // Передаем сообщения в представление
        ]);
    }


    public function create($section_id = null)
    {
        $section = Section::findOrFail($section_id);
        return inertia('Performances/PerformanceFormPage', [
            'section_id' => $section_id,
            'section_name' => $section->name,
        ]);
    }


    public function edit($id)
    {
        $performance = Performance::with(['files', 'section', 'user', 'status',])->findOrFail($id);

        return inertia('Performances/PerformanceFormPage', [
            'performance' => $performance,
        ]);
    }

    protected function storeFiles($files, $fileable)
    {
        foreach ($files as $file) {
            $path = $file->store('uploads');

            File::create([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'fileable_type' => get_class($fileable),
                'fileable_id' => $fileable->id,
            ]);
        }
    }
}
