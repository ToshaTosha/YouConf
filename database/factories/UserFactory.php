<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;
    public function definition(): array
    {
        $faker = FakerFactory::create('ru_RU');
        return [
            'vk_id' => $this->faker->unique()->numberBetween(100000000, 999999999),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'avatar' => $this->faker->imageUrl(640, 480, 'people'),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
