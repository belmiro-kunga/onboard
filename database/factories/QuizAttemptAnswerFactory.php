<?php

namespace Database\Factories;

use App\Models\QuizAttemptAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizAttemptAnswer>
 */
class QuizAttemptAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_attempt_id' => QuizAttempt::factory(),
            'quiz_question_id' => QuizQuestion::factory(),
            'selected_answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'is_correct' => $this->faker->boolean(70), // 70% chance of being correct
            'points_earned' => $this->faker->numberBetween(0, 10),
            'time_spent' => $this->faker->numberBetween(10, 300), // 10 seconds to 5 minutes
        ];
    }

    /**
     * Indicate that the answer is correct.
     */
    public function correct(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_correct' => true,
            'points_earned' => $this->faker->numberBetween(5, 10),
        ]);
    }

    /**
     * Indicate that the answer is incorrect.
     */
    public function incorrect(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_correct' => false,
            'points_earned' => 0,
        ]);
    }

    /**
     * Indicate that the answer was quick.
     */
    public function quick(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_spent' => $this->faker->numberBetween(5, 30),
        ]);
    }

    /**
     * Indicate that the answer took a long time.
     */
    public function slow(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_spent' => $this->faker->numberBetween(120, 300),
        ]);
    }
}