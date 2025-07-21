<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['hr', 'it', 'security', 'processes', 'culture', 'general'];
        $difficultyLevels = ['basic', 'intermediate', 'advanced'];

        return [
            'module_id' => Module::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'instructions' => $this->faker->paragraph(),
            'passing_score' => $this->faker->numberBetween(60, 80),
            'max_attempts' => $this->faker->numberBetween(1, 5),
            'time_limit' => $this->faker->optional(0.7)->numberBetween(10, 60), // em minutos
            'is_active' => $this->faker->boolean(90), // 90% chance de ser ativo
            'difficulty_level' => $this->faker->randomElement($difficultyLevels),
            'category' => $this->faker->randomElement($categories),
            'points_reward' => $this->faker->numberBetween(10, 50),
            'randomize_questions' => $this->faker->boolean(30),
            'show_results_immediately' => $this->faker->boolean(80),
            'allow_review' => $this->faker->boolean(70),
            'generate_certificate' => $this->faker->boolean(40),
        ];
    }

    /**
     * Indicate that the quiz is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the quiz is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the quiz is basic difficulty.
     */
    public function basic(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_level' => 'basic',
        ]);
    }

    /**
     * Indicate that the quiz is intermediate difficulty.
     */
    public function intermediate(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_level' => 'intermediate',
        ]);
    }

    /**
     * Indicate that the quiz is advanced difficulty.
     */
    public function advanced(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_level' => 'advanced',
        ]);
    }

    /**
     * Indicate that the quiz is HR category.
     */
    public function hr(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'hr',
        ]);
    }

    /**
     * Indicate that the quiz is IT category.
     */
    public function it(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'it',
        ]);
    }

    /**
     * Indicate that the quiz is security category.
     */
    public function security(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'security',
        ]);
    }

    /**
     * Indicate that the quiz is processes category.
     */
    public function processes(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'processes',
        ]);
    }

    /**
     * Indicate that the quiz is culture category.
     */
    public function culture(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'culture',
        ]);
    }

    /**
     * Indicate that the quiz has time limit.
     */
    public function withTimeLimit(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_limit' => $this->faker->numberBetween(10, 60),
        ]);
    }

    /**
     * Indicate that the quiz has no time limit.
     */
    public function noTimeLimit(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_limit' => null,
        ]);
    }

    /**
     * Indicate that the quiz randomizes questions.
     */
    public function randomizeQuestions(): static
    {
        return $this->state(fn (array $attributes) => [
            'randomize_questions' => true,
        ]);
    }

    /**
     * Indicate that the quiz shows results immediately.
     */
    public function showResultsImmediately(): static
    {
        return $this->state(fn (array $attributes) => [
            'show_results_immediately' => true,
        ]);
    }

    /**
     * Indicate that the quiz allows review.
     */
    public function allowReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'allow_review' => true,
        ]);
    }

    /**
     * Indicate that the quiz generates certificate.
     */
    public function generateCertificate(): static
    {
        return $this->state(fn (array $attributes) => [
            'generate_certificate' => true,
        ]);
    }

    /**
     * Indicate that the quiz has high passing score.
     */
    public function highPassingScore(): static
    {
        return $this->state(fn (array $attributes) => [
            'passing_score' => $this->faker->numberBetween(80, 90),
        ]);
    }

    /**
     * Indicate that the quiz has low passing score.
     */
    public function lowPassingScore(): static
    {
        return $this->state(fn (array $attributes) => [
            'passing_score' => $this->faker->numberBetween(50, 70),
        ]);
    }

    /**
     * Indicate that the quiz has multiple attempts.
     */
    public function multipleAttempts(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_attempts' => $this->faker->numberBetween(2, 5),
        ]);
    }

    /**
     * Indicate that the quiz has single attempt.
     */
    public function singleAttempt(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_attempts' => 1,
        ]);
    }

    /**
     * Indicate that the quiz has high points reward.
     */
    public function highPoints(): static
    {
        return $this->state(fn (array $attributes) => [
            'points_reward' => $this->faker->numberBetween(30, 50),
        ]);
    }

    /**
     * Indicate that the quiz has low points reward.
     */
    public function lowPoints(): static
    {
        return $this->state(fn (array $attributes) => [
            'points_reward' => $this->faker->numberBetween(5, 15),
        ]);
    }
} 