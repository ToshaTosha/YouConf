<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Section;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function show()
    {
        $schedules = Schedule::with(['thesis.section'])->get();
        $sections = Section::all();

        $formattedSchedules = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'thesis_id' => $schedule->thesis_id,
                'start_time' => $schedule->start_time,
                'duration' => $schedule->duration,
                'end_time' => $schedule->end_time,
                'location' => $schedule->location,
                'thesis_title' => $schedule->thesis->title,
                'section_id' => $schedule->thesis->section_id,
                'user' => [
                    'first_name' => $schedule->thesis->user->first_name,
                    'last_name' => $schedule->thesis->user->last_name,
                ],
            ];
        });

        $sortedSchedules = $formattedSchedules->groupBy('date');

        return Inertia::render('Schedules/Show', [
            'schedules' => $sortedSchedules,
            'sections' => $sections,
        ]);
    }

    public function getThesesBySection($sectionId)
    {
        // Получаем все расписания, относящиеся к заявкам выбранной секции
        $schedules = Schedule::with(['thesis.user', 'thesis.section'])
            ->whereHas('thesis', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->orderBy('start_time')
            ->get();

        // Форматируем данные для ответа
        $formattedSchedules = $schedules->map(function ($schedule) {
            return [
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'location' => $schedule->location,
                'title' => $schedule->thesis->title,
                'description' => $schedule->thesis->description,
                'user' => [
                    'first_name' => $schedule->thesis->user->first_name,
                    'last_name' => $schedule->thesis->user->last_name,
                ],
                'attachments' => $schedule->thesis->media->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'name' => $media->file_name,
                        'original_url' => $media->original_url,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                    ];
                }),
            ];
        });

        $section = Section::findOrFail($sectionId);

        return Inertia::render('Schedules/Index', [
            'theses' => $formattedSchedules,
            'sectionName' => $section->name,
        ]);
    }
}
