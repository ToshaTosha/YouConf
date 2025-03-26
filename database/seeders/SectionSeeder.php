<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/content/sections.json'));
        $sections = json_decode($json, true);

        // Создаем записи в базе данных
        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
