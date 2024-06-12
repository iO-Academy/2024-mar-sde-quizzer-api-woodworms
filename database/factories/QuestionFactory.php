<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'question' => $this->faker->sentence(),
            'hint' => $this->faker->paragraph(),
            'points' => $this->faker->numberBetween(1, 10),
            'quiz_id' => Quiz::factory()
        ];
    }
}
