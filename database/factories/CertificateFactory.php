<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Certificate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'quiz_id' => null,
            'quiz_attempt_id' => null,
            'type' => $this->faker->randomElement(['module', 'category', 'overall', 'excellence', 'speed']),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'reference_id' => $this->faker->numberBetween(1, 100),
            'category' => $this->faker->randomElement(['fundamentals', 'advanced', 'specialized']),
            'certificate_number' => $this->faker->unique()->numerify('CERT-#####'),
            'verification_code' => strtoupper($this->faker->bothify('????####')),
            'issued_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'valid_until' => $this->faker->optional()->dateTimeBetween('now', '+2 years'),
            'file_path' => $this->faker->optional()->filePath(),
            'file_size' => $this->faker->optional()->numberBetween(1000, 1000000),
            'metadata' => [
                'module_title' => $this->faker->words(3, true),
                'completion_date' => $this->faker->date(),
                'points_earned' => $this->faker->numberBetween(50, 500),
            ],
        ];
    }

    /**
     * Indicate that the certificate is for a module.
     */
    public function module(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'module',
            'title' => 'Certificado de Conclusão - ' . $this->faker->words(2, true),
        ]);
    }

    /**
     * Indicate that the certificate is for a category.
     */
    public function category(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'category',
            'title' => 'Certificado de Categoria - ' . $this->faker->words(2, true),
        ]);
    }

    /**
     * Indicate that the certificate is overall completion.
     */
    public function overall(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'overall',
            'title' => 'Certificado de Conclusão Geral',
        ]);
    }

    /**
     * Indicate that the certificate is for excellence.
     */
    public function excellence(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'excellence',
            'title' => 'Certificado de Excelência',
        ]);
    }

    /**
     * Indicate that the certificate is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_until' => $this->faker->dateTimeBetween('now', '+2 years'),
        ]);
    }

    /**
     * Indicate that the certificate is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_until' => $this->faker->dateTimeBetween('-2 years', '-1 year'),
        ]);
    }
} 