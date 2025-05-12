<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thesis;
use App\Models\Chat;
use App\Models\File;
use App\Models\Section;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\ThesisStatusChanged;
use App\Models\Schedule;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ThesisController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::user()->id);
        $statuses = Status::all();
        $userSections = $user->sections()->get();

        if ($user->hasRole('participant')) {
            $theses = Thesis::where('user_id', $user->id)
                ->with(['section', 'user', 'status'])
                ->get();
        } elseif ($user->hasRole('expert')) {
            // Если пользователь эксперт, показываем заявки только из его секций
            $sectionIds = $user->sections()->pluck('sections.id');
            $theses = Thesis::whereIn('section_id', $sectionIds)
                ->with(['section', 'user', 'status'])
                ->get();
        }
        // else {
        //     $theses = Thesis::with(['section', 'user', 'status'])->get();
        // }

        return inertia('Theses/Index', [
            'theses' => $theses,
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

        $thesis = Thesis::with(['status', 'user', 'section'])->findOrFail($id);
        $oldStatus = $thesis->status;

        $thesis->status_id = $request->status_id;
        $thesis->save();

        $newStatus = $thesis->fresh()->status;

        if ($newStatus->id == 2) {
            DB::beginTransaction();
            try {
                // Исправлено: сортируем по end_time, чтобы получить последнее мероприятие
                $lastSchedule = Schedule::where('section_id', $thesis->section_id)
                    ->orderBy('end_time', 'desc') // Изменено с start_time на end_time
                    ->first();

                $calculateTime = function ($time, $addMinutes = 0) {
                    $time = $time ?: '10:00:00';
                    list($h, $m, $s) = explode(':', $time);

                    $minutes = (int)$m + $addMinutes;
                    $hours = (int)$h + floor($minutes / 60);
                    $minutes = $minutes % 60;
                    $hours = $hours % 24;

                    return sprintf('%02d:%02d:00', $hours, $minutes);
                };

                $newStartTime = $lastSchedule
                    ? $lastSchedule->end_time // Берем end_time напрямую без преобразования
                    : '10:00:00';

                // Проверяем, не позже ли 18:00
                if (explode(':', $newStartTime)[0] >= 20) {
                    $newStartTime = '10:00:00';
                }

                // Вычисляем время окончания
                $newEndTime = $calculateTime($newStartTime, 15);
                Log::info($newStartTime);

                // Создаем запись в расписании
                DB::insert('INSERT INTO schedules (
                thesis_id, 
                section_id, 
                start_time, 
                duration, 
                end_time, 
                event_type, 
                title, 
                description, 
                created_at, 
                updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())', [
                    $thesis->id,
                    $thesis->section_id,
                    $newStartTime,
                    15,
                    $newEndTime,
                    'thesis',
                    $thesis->title,
                    $thesis->description
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Schedule creation error: ' . $e->getMessage());
                Log::error('Trace: ' . $e->getTraceAsString());
                return redirect()->back()->with('error', 'Произошла ошибка при создании расписания');
            }
        }

        $thesis->user->notify(
            new ThesisStatusChanged($thesis, $oldStatus, $newStatus)
        );

        if ($oldStatus->id !== $newStatus->id) {
            return redirect()->back()->with('success', [
                'title' => 'Статус изменен',
                'message' => "Статус выступления '{$thesis->title}' изменен на '{$newStatus}'",
                'thesis_id' => $thesis->id
            ]);
        }

        return redirect('/theses')->with('success', 'Статус заявки успешно обновлён');
    }
    public function apply(Request $request, $section_id)
    {

        DB::transaction(function () use ($request, $section_id) {
            $thesis = Thesis::create([
                'title' => $request->title,
                'description' => $request->description,
                'co_authors' => $request->co_authors,
                'user_id' => Auth::id(),
                'status_id' => 1, // Статус "на рассмотрении"
                'section_id' => $section_id,
            ]);
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $thesis->addMedia($file)->toMediaCollection('attachments');
                }
            }

            Chat::create([
                'chatable_type' => Thesis::class, // Указываем тип модели
                'chatable_id' => $thesis->id,
            ]);
        });

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
    }

    public function update(Request $request, $id)
    {
        $thesis = Thesis::findOrFail($id);
        if (in_array($thesis->status_id, [2, 4])) {
            return response()->json(['error' => 'Редактирование выступления невозможно, так как статус не позволяет это.'], 403);
        }
        DB::transaction(function () use ($request, $thesis) {
            $thesis->update([
                'title' => $request->title,
                'description' => $request->description,
                'co_authors' => $request->co_authors,
            ]);
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $thesis->addMedia($file)->toMediaCollection('attachments');
                }
            }
        });

        return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
    }

    public function deleteMedia($thesisId, $mediaId)
    {
        $thesis = Thesis::findOrFail($thesisId);
        $media = $thesis->getMedia('attachments')->find($mediaId);

        if ($media) {
            $media->delete();
            return response()->json(['success' => 'Файл успешно удален.']);
        }

        return redirect()->back();
    }

    public function show($id)
    {
        $thesis = Thesis::with(['section', 'user', 'status', 'chat.messages.user'])->findOrFail($id);

        // Получаем сообщения из чата
        $chat = $thesis->chat;
        $messages = $chat ? $chat->messages : [];
        $mediaFiles = $thesis->getMedia('attachments');
        $statuses = Status::all();

        return inertia('Theses/Show', [
            'thesis' => $thesis,
            'messages' => $messages, // Передаем сообщения в представление
            'mediaFiles' => $mediaFiles,
            'statuses' => $statuses,
        ]);
    }


    public function create($section_id = null)
    {
        $section = Section::findOrFail($section_id);
        return inertia('Theses/ThesisFormPage', [
            'section_id' => $section_id,
            'section_name' => $section->name,
        ]);
    }


    public function edit($id)
    {
        $thesis = Thesis::with(['section', 'user', 'status',])->findOrFail($id);
        $mediaFiles = $thesis->getMedia('attachments');

        return inertia('Theses/ThesisFormPage', [
            'thesis' => $thesis,
            'mediaFiles' => $mediaFiles,
        ]);
    }
}
