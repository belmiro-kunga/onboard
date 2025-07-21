<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserUnitTest extends TestCase
{
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
    public function it_has_correct_table_name(): void
    {
        $user = new User();
        $this->assertEquals('users', $user->getTable());
    }

    /** @test */
    public function it_has_correct_primary_key(): void
    {
        $user = new User();
        $this->assertEquals('id', $user->getKeyName());
    }

    /** @test */
    public function it_uses_timestamps(): void
    {
        $user = new User();
        $this->assertTrue($user->usesTimestamps());
    }

    /** @test */
    public function it_can_set_and_get_attributes(): void
    {
        $user = new User();
        
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        $user->role = 'admin';
        $user->is_active = true;
        
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('admin', $user->role);
        $this->assertTrue($user->is_active);
    }

    /** @test */
    public function it_can_fill_attributes_from_array(): void
    {
        $user = new User();
        $user->fill([
            'name' => 'Filled User',
            'email' => 'filled@example.com',
            'role' => 'manager',
            'is_active' => false
        ]);
        
        $this->assertEquals('Filled User', $user->name);
        $this->assertEquals('filled@example.com', $user->email);
        $this->assertEquals('manager', $user->role);
        $this->assertFalse($user->is_active);
    }

    /** @test */
    public function it_protects_hidden_attributes(): void
    {
        $user = new User([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'remember_token' => 'token123'
        ]);
        
        $array = $user->toArray();
        
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }
}