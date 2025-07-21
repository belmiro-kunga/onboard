<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define o modelo padrão de atributos para Module.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'order_index' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
            'points_reward' => $this->faker->numberBetween(50, 200),
            'estimated_duration' => $this->faker->numberBetween(30, 120),
        ];
    }

    /**
     * Indicate that the module is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the module is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the module is a beginner module.
     */
    public function beginner(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'Módulo Iniciante: ' . $this->faker->sentence(2),
            'points_reward' => $this->faker->numberBetween(25, 75),
            'estimated_duration' => $this->faker->numberBetween(15, 45),
            'order_index' => $this->faker->numberBetween(1, 10),
        ]);
    }

    /**
     * Indicate that the module is an advanced module.
     */
    public function advanced(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'Módulo Avançado: ' . $this->faker->sentence(2),
            'points_reward' => $this->faker->numberBetween(150, 300),
            'estimated_duration' => $this->faker->numberBetween(90, 180),
            'order_index' => $this->faker->numberBetween(50, 100),
        ]);
    }

    /**
     * Indicate that the module has high points reward.
     */
    public function highReward(): static
    {
        return $this->state(fn (array $attributes) => [
            'points_reward' => $this->faker->numberBetween(200, 500),
        ]);
    }

    /**
     * Configure the module after creation to have content.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Module $module) {
            // Create some content for the module
            \App\Models\ModuleContent::factory()
                ->count($this->faker->numberBetween(3, 8))
                ->for($module)
                ->create();
        });
    }
} 