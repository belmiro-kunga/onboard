<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(['learning', 'engagement', 'completion', 'social']),
            'type' => $this->faker->randomElement(['badge', 'trophy', 'certificate']),
            'rarity' => $this->faker->randomElement(['common', 'uncommon', 'rare', 'epic', 'legendary']),
            'points_required' => $this->faker->numberBetween(10, 1000),
            'points_awarded' => $this->faker->numberBetween(5, 500),
            'icon' => 'achievement-' . $this->faker->randomNumber(2) . '.svg',
            'color' => $this->faker->hexColor(),
            'conditions' => [
                'type' => 'points',
                'value' => $this->faker->numberBetween(100, 1000)
            ],
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the achievement is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the achievement is for learning category.
     */
    public function learning(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'learning',
        ]);
    }

    /**
     * Indicate that the achievement is rare.
     */
    public function rare(): static
    {
        return $this->state(fn (array $attributes) => [
            'rarity' => 'rare',
            'points_awarded' => $this->faker->numberBetween(200, 500),
        ]);
    }
}