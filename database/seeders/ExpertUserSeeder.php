<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class ExpertUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'vk_id' => '123456789',
            'first_name' => 'Эксперт',
            'last_name' => 'Тестовый',
            'avatar' => 'https://example.com/avatar.jpg',
            'email' => 'expert@example.com',
            'role' => 'expert',
        ]);
    }
}
