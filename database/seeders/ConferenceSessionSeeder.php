<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Schedule;
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

        // Устанавливаем диапазон дат для расписания
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addDays(7); // Например, на 7 дней вперед

        // Проходим по каждой заявке
        foreach ($applications as $application) {
            // Генерируем случайную дату в диапазоне
            $date = Carbon::createFromFormat('Y-m-d', '2025-02-16'); // Задаем конкретную дату

            // Генерируем случайное время начала в диапазоне с 8:00 до 20:00
            $start_time = Carbon::createFromTime(rand(8, 20), rand(0, 3) * 15); // Случайное время с шагом 15 минут

            // Генерируем продолжительность (60 минут)
            $duration = 60;

            // Вычисляем время окончания
            $end_time = $start_time->copy()->addMinutes($duration);

            // Создаем запись в расписании
            Schedule::create([
                'application_id' => $application->id,
                'date' => $date->toDateString(),
                'start_time' => $start_time->format('H:i'),
                'duration' => $duration,
                'end_time' => $end_time->format('H:i'),
                'location' => 'Location ' . rand(1, 10),
            ]);
        }
    }
}
