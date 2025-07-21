<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserBasicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_user_instance(): void
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
    }

    /** @test */
    public function it_has_fillable_attributes(): void
    {
        $expectedFillable = [
            'name', 'email', 'password', 'role', 'is_active', 'avatar',
            'bio', 'phone', 'birthdate', 'department', 'position',
            'hire_date', 'last_login_at', 'email_verified_at'
        ];

        $user = new User();
        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /** @test */
    public function it_has_hidden_attributes(): void
    {
        $expectedHidden = [
            'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'
        ];

        $user = new User();
        $this->assertEquals($expectedHidden, $user->getHidden());
    }

    /** @test */
    public function it_casts_attributes_correctly(): void
    {
        $user = new User();
        $casts = $user->getCasts();

        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('date', $casts['hire_date']);
        $this->assertEquals('date', $casts['birthdate']);
        $this->assertEquals('boolean', $casts['is_active']);
        $this->assertEquals('boolean', $casts['two_factor_enabled']);
        $this->assertEquals('array', $casts['preferences']);
        $this->assertEquals('hashed', $casts['password']);
    }

    /** @test */
    public function it_can_create_user_with_factory(): void
    {
        $user = User::factory()->make([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue($user->is_active);
    }

    /** @test */
    public function it_can_create_admin_user_with_factory(): void
    {
        $user = User::factory()->admin()->make();
        $this->assertEquals('admin', $user->role);
    }

    /** @test */
    public function it_can_create_manager_user_with_factory(): void
    {
        $user = User::factory()->manager()->make();
        $this->assertEquals('manager', $user->role);
    }

    /** @test */
    public function it_can_create_employee_user_with_factory(): void
    {
        $user = User::factory()->employee()->make();
        $this->assertEquals('employee', $user->role);
    }

    /** @test */
    public function it_can_create_active_user_with_factory(): void
    {
        $user = User::factory()->active()->make();
        $this->assertTrue($user->is_active);
    }

    /** @test */
    public function it_can_create_inactive_user_with_factory(): void
    {
        $user = User::factory()->inactive()->make();
        $this->assertFalse($user->is_active);
    }
}