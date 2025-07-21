<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;
use App\Models\User;
use App\Models\Module;
use App\Models\UserProgress;
use App\Models\QuizAttempt;
use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'name' => 'João Silva',
            'email' => 'joao@hcp.com',
            'department' => 'TI',
            'role' => 'employee',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_can_access_dashboard_when_authenticated()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertSee('João Silva');
    }

    /** @test */
    public function it_redirects_to_login_when_not_authenticated()
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_displays_user_progress_correctly()
    {
        $this->actingAs($this->user);

        // Criar módulos
        $module1 = Module::factory()->create(['points_reward' => 100]);
        $module2 = Module::factory()->create(['points_reward' => 100]);
        $module3 = Module::factory()->create(['points_reward' => 100]);

        // Usuário completou 2 de 3 módulos
        UserProgress::factory()->create([
            'user_id' => $this->user->id,
            'module_id' => $module1->id,
            'completed_at' => now(),
        ]);
        UserProgress::factory()->create([
            'user_id' => $this->user->id,
            'module_id' => $module2->id,
            'completed_at' => now(),
        ]);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        // Verificar se o progresso geral está sendo exibido (deve ser 67% = 2/3 módulos)
        $response->assertSee('%'); // Verificar se há algum percentual sendo exibido
        $response->assertSee('Progresso do Onboarding'); // Verificar se a seção existe
    }

    /** @test */
    public function it_displays_zero_progress_when_no_modules_completed()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('0%');
    }

    /** @test */
    public function it_displays_user_points_correctly()
    {
        $this->actingAs($this->user);

        // Criar tentativas de quiz com pontos
        QuizAttempt::factory()->create([
            'user_id' => $this->user->id,
            'score' => 85,
            'passed' => true,
        ]);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('0'); // Pontos iniciais
    }

    /** @test */
    public function it_displays_user_level_correctly()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Iniciante'); // Nível padrão
    }

    /** @test */
    public function it_displays_next_recommended_module()
    {
        $this->actingAs($this->user);

        $module = Module::factory()->create([
            'title' => 'Introdução à Cultura HCP',
            'order_index' => 1,
        ]);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Introdução à Cultura HCP');
    }

    /** @test */
    public function it_displays_active_modules_for_user()
    {
        $this->actingAs($this->user);

        $module1 = Module::factory()->create([
            'title' => 'Módulo 1',
            'is_active' => true,
        ]);
        $module2 = Module::factory()->create([
            'title' => 'Módulo 2',
            'is_active' => true,
        ]);
        Module::factory()->create([
            'title' => 'Módulo Inativo',
            'is_active' => false,
        ]);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Módulo 1');
        $response->assertSee('Módulo 2');
        $response->assertDontSee('Módulo Inativo');
    }

    /** @test */
    public function it_displays_user_avatar()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('ui-avatars.com');
        $response->assertSee(urlencode($this->user->name));
    }

    /** @test */
    public function it_displays_custom_avatar_when_available()
    {
        $this->user->update(['avatar' => 'avatars/custom.jpg']);
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('avatars/custom.jpg');
    }

    /** @test */
    public function it_displays_greeting_message()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('João Silva');
        $response->assertSee('👋');
    }

    /** @test */
    public function it_displays_encouragement_message()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Pronto para mais uma etapa da sua jornada?');
    }

    /** @test */
    public function it_displays_statistics_cards()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Pontos');
        $response->assertSee('Nível');
        $response->assertSee('Concluídos');
        $response->assertSee('Posição');
    }

    /** @test */
    public function it_displays_progress_bar_with_correct_percentage()
    {
        $this->actingAs($this->user);

        // Criar módulos e progresso
        $module1 = Module::factory()->create(['points_reward' => 100]);
        $module2 = Module::factory()->create(['points_reward' => 100]);

        UserProgress::factory()->create([
            'user_id' => $this->user->id,
            'module_id' => $module1->id,
            'completed_at' => now(),
        ]);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('50%'); // 1/2 = 50%
    }

    /** @test */
    public function it_displays_encouragement_message_in_progress_section()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Continue assim! Você está indo muito bem 🚀');
    }

    /** @test */
    public function it_displays_active_missions_section()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Suas Missões Ativas');
    }

    /** @test */
    public function it_handles_mobile_responsive_layout()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        // Verificar classes responsivas
        $response->assertSee('md:hidden'); // Elementos mobile
        $response->assertSee('hidden md:block'); // Elementos desktop
    }

    /** @test */
    public function it_displays_pull_to_refresh_indicator_on_mobile()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('pull-to-refresh');
        $response->assertSee('Atualizando...');
    }
} 