<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index()
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


        Log::info($formattedSchedules);

        return Inertia::render('Schedules/Index', [
            'schedules' => $sortedSchedules,
            'sections' => $sections,
        ]);
    }
}
