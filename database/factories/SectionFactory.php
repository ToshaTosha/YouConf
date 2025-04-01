<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Section;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Section::class;

    public function definition(): array
    {
        // Устанавливаем локализацию на русский язык
        $faker = FakerFactory::create('ru_RU');

        return [
            'name' => $faker->sentence(3), // Генерация названия секции
            'description' => $faker->paragraph, // Краткое описание
            'full_description' => $faker->text(500), // Полное описание
            'start_date' => $faker->date, // Дата начала
            'end_date' => $faker->date, // Дата окончания
        ];
    }
}
