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

        // Проверяем, является ли пользователь экспертом
        if (!$user->hasRole('expert')) {
            return response()->json(['message' => 'Доступ запрещен.'], 403);
        }

        // Обновляем статус заявки
        $performance = Performance::findOrFail($id);
        $performance->status_id = $request->status_id;
        $performance->save();

        return redirect()->back();
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
        DB::transaction(function () use ($request, $id) {
            // Находим текущую заявку
            $performance = Performance::findOrFail($id);

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
