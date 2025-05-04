<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaticPage;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaticPage::create([
            'slug' => '',
            'title' => 'Главная страница',
            'markdown_content' => '# Добро пожаловать на главную страницу!',
        ]);
    }
}
