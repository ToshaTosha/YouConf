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
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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

    public function apply(Request $request, $section_id)
    {

        DB::transaction(function () use ($request, $section_id) {
            $performance = Performance::create([
                'title' => $request->title,
                'description' => $request->description,
                'co_authors' => $request->co_authors,
                'user_id' => Auth::id(),
                'status_id' => 1, // Статус "на рассмотрении"
                'section_id' => $section_id,
            ]);
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $performance->addMedia($file)->toMediaCollection('attachments');
                }
            }

            Chat::create([
                'chatable_type' => Performance::class, // Указываем тип модели
                'chatable_id' => $performance->id,
            ]);
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
            $performance->update([
                'title' => $request->title,
                'description' => $request->description,
                'co_authors' => $request->co_authors,
            ]);
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $performance->addMedia($file)->toMediaCollection('attachments');
                }
            }
        });

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
    }

    public function deleteMedia($performanceId, $mediaId)
    {
        $performance = Performance::findOrFail($performanceId);
        $media = $performance->getMedia('attachments')->find($mediaId);

        if ($media) {
            $media->delete();
            return response()->json(['success' => 'Файл успешно удален.']);
        }

        return response()->json(['error' => 'Файл не найден.'], 404);
    }

    public function show($id)
    {
        $performance = Performance::with(['section', 'user', 'status', 'chat.messages.user'])->findOrFail($id);

        // Получаем сообщения из чата
        $chat = $performance->chat;
        $messages = $chat ? $chat->messages : [];
        $mediaFiles = $performance->getMedia('attachments');

        return inertia('Performances/Show', [
            'performance' => $performance,
            'messages' => $messages, // Передаем сообщения в представление
            'mediaFiles' => $mediaFiles, // Передаем медиафайлы в представление
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
        $performance = Performance::with(['section', 'user', 'status',])->findOrFail($id);
        $mediaFiles = $performance->getMedia('attachments');

        return inertia('Performances/PerformanceFormPage', [
            'performance' => $performance,
            'mediaFiles' => $mediaFiles,
        ]);
    }
}
