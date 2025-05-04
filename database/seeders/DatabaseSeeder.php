<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            RoleSeeder::class,
            SectionSeeder::class,
            UserSeeder::class,
            StatusesTableSeeder::class,
            LocationSeeder::class,
            PerformanceSeeder::class,
            ConferenceSessionSeeder::class,
            StaticPageSeeder::class,
        ]);
    }
}
