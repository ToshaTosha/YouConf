<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Получаем роли
        $expertRole = Role::where('name', 'expert')->first();
        $participantRole = Role::where('name', 'participant')->first();

        User::factory()
            ->count(5) // Создаем 5 экспертов
            ->create(['role_id' => $expertRole->id]);

        User::factory()
            ->count(5) // Создаем 5 участников
            ->create(['role_id' => $participantRole->id]);
    }
}
