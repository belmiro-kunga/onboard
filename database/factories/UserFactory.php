<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define o modelo padrão de atributos para User.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'department' => $this->faker->randomElement(['Financeiro', 'RH', 'TI', 'Operações']),
            'role' => $this->faker->randomElement(['admin', 'manager', 'employee']),
            'is_active' => true,
            'avatar' => null,
            'phone' => $this->faker->phoneNumber(),
            'position' => $this->faker->jobTitle(),
            'hire_date' => $this->faker->date(),
            'last_login_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a manager.
     */
    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'manager',
        ]);
    }

    /**
     * Indicate that the user is an employee.
     */
    public function employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'employee',
        ]);
    }

    /**
     * Indicate that the user is from IT department.
     */
    public function it(): static
    {
        return $this->state(fn (array $attributes) => [
            'department' => 'TI',
        ]);
    }

    /**
     * Indicate that the user is from HR department.
     */
    public function hr(): static
    {
        return $this->state(fn (array $attributes) => [
            'department' => 'RH',
        ]);
    }

    /**
     * Indicate that the user has an avatar.
     */
    public function withAvatar(): static
    {
        return $this->state(fn (array $attributes) => [
            'avatar' => 'avatars/' . fake()->image('public/storage/avatars', 200, 200, 'people', false),
        ]);
    }

    /**
     * Indicate that the user has phone number.
     */
    public function withPhone(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => fake()->phoneNumber(),
        ]);
    }

    /**
     * Indicate that the user has position.
     */
    public function withPosition(): static
    {
        return $this->state(fn (array $attributes) => [
            'position' => fake()->jobTitle(),
        ]);
    }

    /**
     * Indicate that the user has hire date.
     */
    public function withHireDate(): static
    {
        return $this->state(fn (array $attributes) => [
            'hire_date' => fake()->dateTimeBetween('-2 years', 'now'),
        ]);
    }

    /**
     * Indicate that the user has recent login.
     */
    public function withRecentLogin(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_login_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }
}
