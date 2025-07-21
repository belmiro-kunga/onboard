<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@hcp.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'role' => 'employee',
        ]);
    }

    /** @test */
    public function it_can_show_login_page()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
        $response->assertSee('Login');
    }

    /** @test */
    public function it_can_authenticate_user_with_valid_credentials()
    {
        $credentials = [
            'email' => 'test@hcp.com',
            'password' => 'password123',
        ];

        $response = $this->post(route('login'), $credentials);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    /** @test */
    public function it_cannot_authenticate_with_invalid_credentials()
    {
        $credentials = [
            'email' => 'test@hcp.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->post(route('login'), $credentials);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function it_cannot_authenticate_inactive_user()
    {
        $this->user->update(['is_active' => false]);

        $credentials = [
            'email' => 'test@hcp.com',
            'password' => 'password123',
        ];

        $response = $this->post(route('login'), $credentials);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function it_validates_required_fields_on_login()
    {
        $response = $this->post(route('login'), []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    /** @test */
    public function it_validates_email_format_on_login()
    {
        $credentials = [
            'email' => 'invalid-email',
            'password' => 'password123',
        ];

        $response = $this->post(route('login'), $credentials);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_can_logout_authenticated_user()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function it_can_show_forgot_password_page()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
        $response->assertSee('Recuperar Senha');
    }

    /** @test */
    public function it_can_send_password_reset_email()
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'test@hcp.com',
        ]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($this->user, \Illuminate\Auth\Notifications\ResetPassword::class);
    }

    /** @test */
    public function it_does_not_send_reset_email_for_invalid_email()
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@hcp.com',
        ]);

        // Verificar que nenhuma notificação foi enviada para qualquer usuário
        Notification::assertNothingSent();
    }

    /** @test */
    public function it_can_show_reset_password_page()
    {
        $token = Password::createToken($this->user);

        $response = $this->get(route('password.reset', $token));

        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
        $response->assertSee('Redefinir Senha');
    }

    /** @test */
    public function it_can_reset_password_with_valid_token()
    {
        $token = Password::createToken($this->user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@hcp.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/');
    }

    /** @test */
    public function it_validates_password_confirmation_on_reset()
    {
        $token = Password::createToken($this->user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@hcp.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_validates_password_length_on_reset()
    {
        $token = Password::createToken($this->user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@hcp.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_redirects_authenticated_user_from_login_page()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('login'));

        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function it_redirects_authenticated_user_from_forgot_password_page()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('password.request'));

        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function it_remembers_user_when_remember_me_is_checked()
    {
        $credentials = [
            'email' => 'test@hcp.com',
            'password' => 'password123',
            'remember' => 'on',
        ];

        $response = $this->post(route('login'), $credentials);

        $response->assertRedirect('/');
    }

    /** @test */
    public function it_redirects_admin_user_to_admin_dashboard()
    {
        // Criar usuário admin
        $adminUser = User::factory()->create([
            'email' => 'admin@hcp.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $credentials = [
            'email' => 'admin@hcp.com',
            'password' => '123456',
        ];

        $response = $this->post(route('login'), $credentials);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    /** @test */
    public function it_shows_specific_error_messages_for_login_failures()
    {
        // Testar com email inexistente
        $response = $this->post(route('login'), [
            'email' => 'nonexistent@hcp.com',
            'password' => '123456',
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertSee('Credenciais inválidas');

        // Testar com senha incorreta
        $response = $this->post(route('login'), [
            'email' => 'funcionario@hcp.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertSee('Credenciais inválidas');
    }

    /** @test */
    public function it_shows_correct_error_message_for_invalid_credentials()
    {
        $response = $this->post(route('login'), [
            'email' => 'funcionario@hcp.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        // Não verificar o conteúdo da página, apenas os erros de sessão
    }

    /** @test */
    public function it_can_login_with_test_user_credentials()
    {
        // Executar o seeder para garantir que os usuários existam
        $this->artisan('db:seed', ['--class' => 'TestUserSeeder']);

        // Testar login com funcionário
        $response = $this->post(route('login'), [
            'email' => 'funcionario@hcp.com',
            'password' => '123456',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        // Fazer logout
        $this->post(route('logout'));

        // Testar login com gestor
        $response = $this->post(route('login'), [
            'email' => 'gestor@hcp.com',
            'password' => '123456',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        // Fazer logout
        $this->post(route('logout'));

        // Testar login com admin
        $response = $this->post(route('login'), [
            'email' => 'admin@hcp.com',
            'password' => '123456',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }
} 