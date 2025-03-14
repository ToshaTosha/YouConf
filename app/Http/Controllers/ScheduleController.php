<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Models\Application;

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


        Log::info($formattedSchedules);

        return Inertia::render('Schedules/Show', [
            'schedules' => $sortedSchedules,
            'sections' => $sections,
        ]);
    }
    public function getApplicationsBySection($sectionId)
    {
        $applications = Application::with(['user', 'section', 'schedule'])
            ->where('section_id', $sectionId)
            ->get();

        // Форматируем данные для ответа
        $formattedApplications = $applications->map(function ($application) {
            return [
                'title' => $application->title,
                'description' => $application->description,
                'user' => [
                    'first_name' => $application->user->first_name,
                    'last_name' => $application->user->last_name,
                    'email' => $application->user->email,
                ],
                'schedule' => [
                    'date' => $application->schedule->date,
                    'start_time' => $application->schedule->start_time,
                    'end_time' => $application->schedule->end_time,
                    'location' => $application->schedule->location,
                ],
            ];
        });

        return response()->json($formattedApplications);
    }
}
