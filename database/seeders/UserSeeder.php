<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user1->assignRole('participant');

        $user2 = User::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user2->assignRole('expert');

        $user3 = User::create([
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user3->assignRole('organizer');
    }
}
