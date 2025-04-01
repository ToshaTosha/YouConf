<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Получаем роли
        $expertRole = Role::where('name', 'expert')->first();
        $participantRole = Role::where('name', 'participant')->first();

        // Создаем 5 экспертов
        $experts = User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($expertRole) {
                $user->assignRole($expertRole);
            });

        // Создаем 5 участников
        $participants = User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($participantRole) {
                $user->assignRole($participantRole);
            });

        // Выводим информацию о созданных пользователях и их ролях
        foreach ($experts as $user) {
            $this->command->info("Пользователь: {$user->id}, Роль: Expert");
        }

        foreach ($participants as $user) {
            $this->command->info("Пользователь: {$user->id}, Роль: Participant");
        }
    }
}
