<?php

namespace Database\Factories;

use App\Models\PointsHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PointsHistory>
 */
class PointsHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'points' => $this->faker->numberBetween(1, 100),
            'reason' => $this->faker->sentence(),
            'reference_type' => $this->faker->randomElement(['quiz_completion', 'module_completion', 'achievement', 'bonus']),
            'reference_id' => $this->faker->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the points are for quiz completion.
     */
    public function quizCompletion(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference_type' => 'quiz_completion',
            'reason' => 'Quiz completed successfully',
            'points' => $this->faker->numberBetween(10, 50),
        ]);
    }

    /**
     * Indicate that the points are for module completion.
     */
    public function moduleCompletion(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference_type' => 'module_completion',
            'reason' => 'Module completed successfully',
            'points' => $this->faker->numberBetween(20, 100),
        ]);
    }

    /**
     * Indicate that the points are for achievement.
     */
    public function achievement(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference_type' => 'achievement',
            'reason' => 'Achievement unlocked',
            'points' => $this->faker->numberBetween(50, 200),
        ]);
    }

    /**
     * Indicate that the points are bonus points.
     */
    public function bonus(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference_type' => 'bonus',
            'reason' => 'Bonus points awarded',
            'points' => $this->faker->numberBetween(5, 25),
        ]);
    }
}