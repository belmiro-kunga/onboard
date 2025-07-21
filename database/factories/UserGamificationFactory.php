<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserGamification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserGamification>
 */
class UserGamificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserGamification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_points' => $this->faker->numberBetween(0, 10000),
            'current_level' => $this->faker->randomElement(['Rookie', 'Beginner', 'Explorer', 'Intermediate', 'Advanced', 'Expert', 'Master']),
            'rank_position' => $this->faker->numberBetween(1, 100),
            'achievements_count' => $this->faker->numberBetween(0, 20),
            'streak_days' => $this->faker->numberBetween(0, 30),
            'longest_streak' => $this->faker->numberBetween(0, 100),
            'last_activity_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'level_progress' => $this->faker->numberBetween(0, 100),
            'badges' => [],
            'statistics' => [
                'modules_completed' => $this->faker->numberBetween(0, 50),
                'quizzes_passed' => $this->faker->numberBetween(0, 100),
                'total_time_spent' => $this->faker->numberBetween(0, 1000),
            ],
        ];
    }

    /**
     * Indicate that the user has no points.
     */
    public function zeroPoints(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_points' => 0,
            'current_level' => 'Rookie',
            'level_progress' => 0,
        ]);
    }

    /**
     * Indicate that the user is a beginner.
     */
    public function beginner(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_points' => $this->faker->numberBetween(100, 499),
            'current_level' => 'Beginner',
        ]);
    }

    /**
     * Indicate that the user is an expert.
     */
    public function expert(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_points' => $this->faker->numberBetween(5000, 9999),
            'current_level' => 'Expert',
        ]);
    }

    /**
     * Indicate that the user has an active streak.
     */
    public function withActiveStreak(): static
    {
        return $this->state(fn (array $attributes) => [
            'streak_days' => $this->faker->numberBetween(1, 30),
            'last_activity_date' => now(),
        ]);
    }
} 