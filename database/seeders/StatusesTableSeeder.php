<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $statuses = [
            ['name' => 'На рассмотрении'],
            ['name' => 'Принято'],
            ['name' => 'Отклонено'],
        ];

        DB::table('statuses')->insert($statuses);
    }
}
