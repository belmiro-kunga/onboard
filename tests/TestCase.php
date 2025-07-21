<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Skip seeding for now to avoid migration issues
        // $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /**
     * Create an authenticated user for testing.
     */
    protected function createAuthenticatedUser(array $attributes = []): \App\Models\User
    {
        $user = \App\Models\User::factory()->create($attributes);
        $this->actingAs($user);
        return $user;
    }

    /**
     * Create an admin user for testing.
     */
    protected function createAdminUser(array $attributes = []): \App\Models\User
    {
        $user = \App\Models\User::factory()->admin()->create($attributes);
        $this->actingAs($user);
        return $user;
    }

    /**
     * Assert that a response contains validation errors for specific fields.
     */
    protected function assertValidationErrors(array $fields): void
    {
        $this->assertSessionHasErrors($fields);
    }

    /**
     * Assert that a model exists in the database with specific attributes.
     */
    protected function assertDatabaseHasModel(string $model, array $attributes): void
    {
        $this->assertDatabaseHas((new $model)->getTable(), $attributes);
    }

    /**
     * Assert that a model does not exist in the database with specific attributes.
     */
    protected function assertDatabaseMissingModel(string $model, array $attributes): void
    {
        $this->assertDatabaseMissing((new $model)->getTable(), $attributes);
    }
}
