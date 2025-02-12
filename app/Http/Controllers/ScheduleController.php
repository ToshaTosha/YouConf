<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('application')->orderBy('scheduled_at')->get();

        // Группируем расписание по дате
        $groupedSchedules = $schedules->groupBy(function ($schedule) {
            return \Carbon\Carbon::parse($schedule->scheduled_at)->format('Y-m-d');
        });

        return Inertia::render('Schedules/Index', [
            'schedule' => $groupedSchedules,
        ]);
    }
}
