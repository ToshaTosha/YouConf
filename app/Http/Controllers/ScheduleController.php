<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Section;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function show()
    {
        $schedules = Schedule::with(['performance.section'])->get();
        $sections = Section::all();

        $formattedSchedules = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'performance_id' => $schedule->performance_id,
                'start_time' => $schedule->start_time,
                'duration' => $schedule->duration,
                'end_time' => $schedule->end_time,
                'location' => $schedule->location,
                'performance_title' => $schedule->performance->title,
                'section_id' => $schedule->performance->section_id,
                'user' => [
                    'first_name' => $schedule->performance->user->first_name,
                    'last_name' => $schedule->performance->user->last_name,
                ],
            ];
        });

        $sortedSchedules = $formattedSchedules->groupBy('date');

        return Inertia::render('Schedules/Show', [
            'schedules' => $sortedSchedules,
            'sections' => $sections,
        ]);
    }

    public function getPerformancesBySection($sectionId)
    {
        // Получаем все расписания, относящиеся к заявкам выбранной секции
        $schedules = Schedule::with(['performance.user', 'performance.section'])
            ->whereHas('performance', function ($query) use ($sectionId) {
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
                'title' => $schedule->performance->title,
                'description' => $schedule->performance->description,
                'user' => [
                    'first_name' => $schedule->performance->user->first_name,
                    'last_name' => $schedule->performance->user->last_name,
                ],
                'attachments' => $schedule->performance->media->map(function ($media) {
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
            'performances' => $formattedSchedules,
            'sectionName' => $section->name,
        ]);
    }
}
