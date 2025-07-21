<?php

namespace Database\Factories;

use App\Models\QuizQuestion;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizQuestion>
 */
class QuizQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $questionType = $this->faker->randomElement(['multiple_choice', 'single_choice', 'true_false']);
        
        return [
            'quiz_id' => Quiz::factory(),
            'question' => $this->faker->sentence() . '?',
            'question_type' => $questionType,
            'options' => $this->generateOptions($questionType),
            'correct_answer' => $this->generateCorrectAnswer($questionType),
            'explanation' => $this->faker->optional()->paragraph(),
            'points' => $this->faker->numberBetween(1, 10),
            'order_index' => $this->faker->numberBetween(1, 20),
            'is_active' => true,
        ];
    }

    /**
     * Generate options based on question type.
     */
    private function generateOptions(string $questionType): array
    {
        switch ($questionType) {
            case 'multiple_choice':
            case 'single_choice':
                return [
                    'A' => $this->faker->sentence(4),
                    'B' => $this->faker->sentence(4),
                    'C' => $this->faker->sentence(4),
                    'D' => $this->faker->sentence(4),
                ];
            case 'true_false':
                return [
                    'true' => 'Verdadeiro',
                    'false' => 'Falso',
                ];
            default:
                return [];
        }
    }

    /**
     * Generate correct answer based on question type.
     */
    private function generateCorrectAnswer(string $questionType): array
    {
        switch ($questionType) {
            case 'multiple_choice':
                return $this->faker->randomElements(['A', 'B', 'C', 'D'], $this->faker->numberBetween(1, 2));
            case 'single_choice':
                return [$this->faker->randomElement(['A', 'B', 'C', 'D'])];
            case 'true_false':
                return [$this->faker->randomElement(['true', 'false'])];
            default:
                return [];
        }
    }

    /**
     * Indicate that the question is multiple choice.
     */
    public function multipleChoice(): static
    {
        return $this->state(fn (array $attributes) => [
            'question_type' => 'multiple_choice',
            'options' => [
                'A' => $this->faker->sentence(4),
                'B' => $this->faker->sentence(4),
                'C' => $this->faker->sentence(4),
                'D' => $this->faker->sentence(4),
            ],
            'correct_answer' => $this->faker->randomElements(['A', 'B', 'C', 'D'], $this->faker->numberBetween(1, 2)),
        ]);
    }

    /**
     * Indicate that the question is single choice.
     */
    public function singleChoice(): static
    {
        return $this->state(fn (array $attributes) => [
            'question_type' => 'single_choice',
            'options' => [
                'A' => $this->faker->sentence(4),
                'B' => $this->faker->sentence(4),
                'C' => $this->faker->sentence(4),
                'D' => $this->faker->sentence(4),
            ],
            'correct_answer' => [$this->faker->randomElement(['A', 'B', 'C', 'D'])],
        ]);
    }

    /**
     * Indicate that the question is true/false.
     */
    public function trueFalse(): static
    {
        return $this->state(fn (array $attributes) => [
            'question_type' => 'true_false',
            'options' => [
                'true' => 'Verdadeiro',
                'false' => 'Falso',
            ],
            'correct_answer' => [$this->faker->randomElement(['true', 'false'])],
        ]);
    }

    /**
     * Indicate that the question is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}