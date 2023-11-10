<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(2),
            'short_description' => fake()->text(50),
            'long_description' => fake()->text(200),
            'published_at' => fake()->date(),
            'user_id' => function () {
                return User::all()->random()->id;
            }
        ];
    }
}
