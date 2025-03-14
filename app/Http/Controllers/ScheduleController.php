<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function show()
    {
        $schedules = Schedule::with(['application.section'])->get();
        $sections = Section::all();

        $formattedSchedules = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'application_id' => $schedule->application_id,
                'date' => $schedule->date,
                'start_time' => $schedule->start_time,
                'duration' => $schedule->duration,
                'end_time' => $schedule->end_time,
                'location' => $schedule->location,
                'application_title' => $schedule->application->title, // Извлекаем заголовок приложения
                'section_id' => $schedule->application->section_id, // Извлекаем имя секции
            ];
        });

        $sortedSchedules = $formattedSchedules->groupBy('date');

        return Inertia::render('Schedules/Show', [
            'schedules' => $sortedSchedules,
            'sections' => $sections,
        ]);
    }

    public function getApplicationsBySection($sectionId)
    {
        // Получаем все расписания, относящиеся к заявкам выбранной секции
        $schedules = Schedule::with(['application.user', 'application.section'])
            ->whereHas('application', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Форматируем данные для ответа
        $formattedSchedules = $schedules->map(function ($schedule) {
            return [
                'date' => $schedule->date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'location' => $schedule->location,
                'title' => $schedule->application->title,
                'description' => $schedule->application->description,
                'user' => [
                    'first_name' => $schedule->application->user->first_name,
                    'last_name' => $schedule->application->user->last_name,
                ],
            ];
        });

        $section = Section::findOrFail($sectionId);

        return Inertia::render('Schedules/Index', [
            'performances' => $formattedSchedules,
            'sectionName' => $section->name,
        ]);
    }
}
