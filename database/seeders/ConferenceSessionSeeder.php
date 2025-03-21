<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Schedule;
use App\Models\Location;
use Carbon\Carbon;

class ConferenceSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем только заявки со статусом "Принято" (status_id = 2)
        $applications = Application::where('status_id', 2)->get();
        $locations = Location::all();

        // Устанавливаем диапазон дат для расписания
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addDays(7); // Например, на 7 дней вперед
        $durations = [15, 30, 45, 60];

        // Проходим по каждой заявке
        foreach ($applications as $application) {
            // Генерируем случайную дату в диапазоне
            $date = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));

            $start_time = Carbon::createFromTime(rand(8, 20), rand(0, 3) * 15);

            $duration = $durations[array_rand($durations)];
            $end_time = $start_time->copy()->addMinutes($duration);

            $location = $locations->random();

            // Создаем запись в расписании
            Schedule::create([
                'application_id' => $application->id,
                'date' => $date->toDateString(),
                'start_time' => $start_time->format('H:i'),
                'duration' => $duration,
                'end_time' => $end_time->format('H:i'),
                'location_id' => $location->id,
            ]);
        }
    }
}
