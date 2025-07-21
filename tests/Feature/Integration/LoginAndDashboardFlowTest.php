<?php

namespace Tests\Feature\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginAndDashboardFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_navigate_dashboard_update_profile_and_logout()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'role' => 'employee',
        ]);

        // Login
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        // Acessar dashboard
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee($user->name);

        // Navegar para perfil
        $response = $this->get(route('profile.index'));
        $response->assertStatus(200);
        $response->assertSee($user->email);

        // Atualizar perfil
        $response = $this->put(route('profile.update'), [
            'name' => 'Novo Nome',
            'email' => 'novonome@example.com',
            'bio' => 'Bio de teste',
        ]);
        $response->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Novo Nome',
            'email' => 'novonome@example.com',
            'bio' => 'Bio de teste',
        ]);

        // Logout
        $response = $this->post('/logout');
        $this->assertTrue(in_array($response->headers->get('Location'), [url('/login'), url('/')]));
        $this->assertGuest();
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard()
    {
        $admin = User::factory()->create([
            'password' => bcrypt('adminpass'),
            'role' => 'admin',
        ]);
        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'adminpass',
        ]);
        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        // Acessar admin dashboard
        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee($admin->name);
        // Logout
        $response = $this->post('/logout');
        $this->assertTrue(in_array($response->headers->get('Location'), [url('/login'), url('/')]));
        $this->assertGuest();
    }
} 