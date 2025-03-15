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

        User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($expertRole) {
                $user->assignRole($expertRole);
            });

        // Создаем 5 участников
        User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($participantRole) {
                $user->assignRole($participantRole);
            });
    }
}
