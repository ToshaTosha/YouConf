<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Status;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Chat;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'user_id' => $this->faker->numberBetween(1, 10),
            'status_id' => Status::inRandomOrder()->first()->id,
            'section_id' => Section::inRandomOrder()->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Application $application) {
            // Создаем чат для заявки
            Chat::create([
                'chatable_type' => Application::class,
                'chatable_id' => $application->id,
            ]);
        });
    }
}
