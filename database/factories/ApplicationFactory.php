<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Status;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'user_id' => 11,
            'status_id' => Status::inRandomOrder()->first()->id,
            'section_id' => Section::inRandomOrder()->first()->id,
        ];
    }
}
