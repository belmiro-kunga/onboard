<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAttempt>
 */
class QuizAttemptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $completedAt = $this->faker->optional(0.8)->dateTimeBetween($startedAt, 'now');
        $score = $this->faker->numberBetween(0, 100);
        $passed = $completedAt ? ($score >= 70) : false;

        return [
            'user_id' => User::factory(),
            'quiz_id' => Quiz::factory(),
            'attempt_number' => $this->faker->numberBetween(1, 3),
            'score' => $score,
            'passed' => $passed,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'time_spent' => $completedAt ? $this->faker->numberBetween(300, 1800) : null, // 5-30 minutos
            'answers' => [
                'question_1' => ['selected' => 'a', 'correct' => true, 'time_spent' => 45],
                'question_2' => ['selected' => 'b', 'correct' => false, 'time_spent' => 30],
                'question_3' => ['selected' => 'c', 'correct' => true, 'time_spent' => 60],
            ],
            'results' => [
                'overall_feedback' => 'Bom trabalho! Continue estudando.',
                'strengths' => ['Conhecimento básico sólido', 'Boa compreensão dos conceitos'],
                'areas_for_improvement' => ['Atenção aos detalhes', 'Revisar políticas específicas'],
            ],
        ];
    }

    /**
     * Indicate that the attempt is passed.
     */
    public function passed(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => $this->faker->numberBetween(70, 100),
            'passed' => true,
            'completed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the attempt is failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => $this->faker->numberBetween(0, 69),
            'passed' => false,
            'completed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the attempt is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => null,
            'passed' => false,
            'completed_at' => null,
            'time_spent' => null,
        ]);
    }

    /**
     * Indicate that the attempt has high score.
     */
    public function highScore(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => $this->faker->numberBetween(90, 100),
            'passed' => true,
        ]);
    }

    /**
     * Indicate that the attempt has low score.
     */
    public function lowScore(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => $this->faker->numberBetween(0, 50),
            'passed' => false,
        ]);
    }

    /**
     * Indicate that the attempt is first attempt.
     */
    public function firstAttempt(): static
    {
        return $this->state(fn (array $attributes) => [
            'attempt_number' => 1,
        ]);
    }

    /**
     * Indicate that the attempt is retry attempt.
     */
    public function retry(): static
    {
        return $this->state(fn (array $attributes) => [
            'attempt_number' => $this->faker->numberBetween(2, 3),
        ]);
    }

    /**
     * Indicate that the attempt is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'started_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'completed_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ]);
    }

    /**
     * Indicate that the attempt is old.
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'started_at' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
            'completed_at' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
        ]);
    }

    /**
     * Indicate that the attempt has detailed answers.
     */
    public function withDetailedAnswers(): static
    {
        return $this->state(fn (array $attributes) => [
            'answers' => [
                'question_1' => [
                    'selected' => 'a',
                    'correct' => true,
                    'time_spent' => 45,
                    'explanation' => 'Resposta correta: A política de segurança exige autenticação de dois fatores.',
                ],
                'question_2' => [
                    'selected' => 'b',
                    'correct' => false,
                    'time_spent' => 30,
                    'explanation' => 'Resposta incorreta: A resposta correta é C. Consulte o manual de procedimentos.',
                ],
                'question_3' => [
                    'selected' => 'c',
                    'correct' => true,
                    'time_spent' => 60,
                    'explanation' => 'Resposta correta: O código de ética da empresa proíbe compartilhamento de informações confidenciais.',
                ],
                'question_4' => [
                    'selected' => 'd',
                    'correct' => true,
                    'time_spent' => 25,
                    'explanation' => 'Resposta correta: O horário de funcionamento é das 9h às 18h.',
                ],
                'question_5' => [
                    'selected' => 'a',
                    'correct' => false,
                    'time_spent' => 40,
                    'explanation' => 'Resposta incorreta: A resposta correta é B. Verifique a política de benefícios.',
                ],
            ],
        ]);
    }

    /**
     * Indicate that the attempt has detailed feedback.
     */
    public function withDetailedFeedback(): static
    {
        return $this->state(fn (array $attributes) => [
            'results' => [
                'overall_feedback' => 'Excelente trabalho! Você demonstrou um bom entendimento dos conceitos fundamentais.',
                'strengths' => [
                    'Conhecimento sólido das políticas básicas',
                    'Boa compreensão dos procedimentos de segurança',
                    'Atenção aos detalhes importantes',
                ],
                'areas_for_improvement' => [
                    'Revisar políticas específicas de RH',
                    'Aprofundar conhecimento sobre procedimentos de TI',
                    'Prestar mais atenção aos prazos e deadlines',
                ],
                'recommendations' => [
                    'Complete o módulo de políticas avançadas',
                    'Participe do workshop de segurança da informação',
                    'Revise o manual de procedimentos regularmente',
                ],
                'score_breakdown' => [
                    'security' => 85,
                    'hr' => 70,
                    'it' => 90,
                    'processes' => 75,
                ],
            ],
        ]);
    }
} 