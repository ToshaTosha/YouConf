<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Создаем роли
        Role::create(['name' => 'participant']); // Участник
        Role::create(['name' => 'expert']);      // Эксперт
        Role::create(['name' => 'admin']);       // Администратор
    }
}
