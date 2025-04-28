<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Section;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->isProduction()) {
            $this->seedDevUsers();
        } else {
            $this->seedProductionUsers();
        }
    }
    protected function seedDevUsers(): void
    {
        // Получаем роли
        $expertRole = Role::where('name', 'expert')->first();
        $participantRole = Role::where('name', 'participant')->first();

        $sections = Section::all();
        $experts = User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($expertRole, $sections) {
                $user->assignRole($expertRole);
                // Назначаем случайные секции (1-3 секции на эксперта)
                $user->sections()->attach(
                    $sections->random(rand(1, 3))->pluck('id')->toArray()
                );
            });

        // Создаем 5 участников
        $participants = User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($participantRole) {
                $user->assignRole($participantRole);
            });
    }


    protected function seedProductionUsers(): void
    {
        $expertRole = Role::where('name', 'expert')->first();
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'first_name' => 'Admin',
            'last_name' => 'System',
            'vk_id' => rand(100000000, 999999999),
            'avatar' => 'https://i.pravatar.cc/300?u=admin'
        ]);

        $admin->assignRole($expertRole);
    }
}
