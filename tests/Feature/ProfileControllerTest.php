<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_routes()
    {
        $response = $this->get(route('profile.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile.index'));
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_authenticated_user_can_edit_profile()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Editar Perfil');
    }

    public function test_authenticated_user_can_update_profile()
    {
        $user = User::factory()->create();
        $newName = 'Novo Nome';
        $newEmail = 'novoemail@example.com';
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $newName,
            'email' => $newEmail,
            'bio' => 'Nova bio',
        ]);
        $response->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newName,
            'email' => $newEmail,
            'bio' => 'Nova bio',
        ]);
    }

    public function test_user_cannot_update_profile_with_invalid_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => '',
            'email' => 'not-an-email',
        ]);
        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_authenticated_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);
        $response = $this->actingAs($user)->put(route('profile.update-password'), [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $response->assertRedirect(route('profile.index'));
        $this->assertTrue(
            \Hash::check('newpassword123', $user->fresh()->password)
        );
    }

    public function test_user_cannot_change_password_with_invalid_data()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);
        $response = $this->actingAs($user)->put(route('profile.update-password'), [
            'current_password' => 'wrongpassword',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);
        $response->assertSessionHasErrors(['current_password', 'password']);
    }
} 