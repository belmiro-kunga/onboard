<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Module;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProgress>
 */
class UserProgressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserProgress::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'module_id' => Module::factory(),
            'status' => $this->faker->randomElement(['not_started', 'in_progress', 'completed', 'paused']),
            'progress_percentage' => $this->faker->numberBetween(0, 100),
            'time_spent' => $this->faker->numberBetween(0, 3600), // em segundos
            'last_accessed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'started_at' => $this->faker->dateTimeBetween('-2 months', '-1 month'),
            'completed_at' => null,
            'current_section' => $this->faker->optional(0.3)->word(),
            'notes' => $this->faker->optional(0.3)->paragraph(),
            'bookmarks' => $this->faker->optional(0.2)->randomElements([
                ['time' => 120, 'note' => 'Ponto importante'],
                ['time' => 300, 'note' => 'Revisar depois'],
                ['time' => 600, 'note' => 'Conceito chave'],
            ], $this->faker->numberBetween(1, 3)),
        ];
    }

    /**
     * Indicate that the progress is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'progress_percentage' => 100,
            'completed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the progress is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'progress_percentage' => $this->faker->numberBetween(1, 99),
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the progress is just started.
     */
    public function started(): static
    {
        return $this->state(fn (array $attributes) => [
            'progress_percentage' => $this->faker->numberBetween(1, 10),
            'completed_at' => null,
            'started_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the progress has high time spent.
     */
    public function highTimeSpent(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_spent' => $this->faker->numberBetween(1800, 7200), // 30-120 minutos
        ]);
    }

    /**
     * Indicate that the progress has low time spent.
     */
    public function lowTimeSpent(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_spent' => $this->faker->numberBetween(0, 900), // 0-15 minutos
        ]);
    }

    /**
     * Indicate that the progress has notes.
     */
    public function withNotes(): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $this->faker->paragraphs(2, true),
        ]);
    }

    /**
     * Indicate that the progress has bookmarks.
     */
    public function withBookmarks(): static
    {
        return $this->state(fn (array $attributes) => [
            'bookmarks' => [
                ['time' => 120, 'note' => 'Ponto importante sobre políticas'],
                ['time' => 300, 'note' => 'Revisar procedimentos de segurança'],
                ['time' => 600, 'note' => 'Conceito chave da cultura empresarial'],
            ],
        ]);
    }

    /**
     * Indicate that the progress has quiz scores.
     */
    public function withQuizScores(): static
    {
        return $this->state(fn (array $attributes) => [
            'quiz_scores' => [
                ['quiz_id' => 1, 'score' => 85, 'attempts' => 1, 'completed_at' => now()],
                ['quiz_id' => 2, 'score' => 92, 'attempts' => 2, 'completed_at' => now()],
                ['quiz_id' => 3, 'score' => 78, 'attempts' => 1, 'completed_at' => now()],
            ],
        ]);
    }

    /**
     * Indicate that the progress is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_accessed_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'started_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
        ]);
    }

    /**
     * Indicate that the progress is old.
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_accessed_at' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
            'started_at' => $this->faker->dateTimeBetween('-6 months', '-3 months'),
        ]);
    }
} 