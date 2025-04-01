<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем 10 мест проведения
        $locations = [
            'Зал 1',
            'Зал 2',
            'Зал 3',
            'Конференц-зал A',
            'Конференц-зал B',
            'Аудитория 101',
            'Аудитория 102',
            'Аудитория 103',
            'Онлайн',
            'Открытая площадка',
        ];

        foreach ($locations as $locationName) {
            Location::create([
                'name' => $locationName,
            ]);
        }
    }
}
