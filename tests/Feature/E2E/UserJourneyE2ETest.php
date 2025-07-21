<?php

declare(strict_types=1);

namespace Tests\Feature\E2E;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Certificate;
use App\Models\UserGamification;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserJourneyE2ETest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Teste E2E da jornada completa de onboarding
     */
    public function test_complete_onboarding_journey_e2e(): void
    {
        // Arrange
        $user = User::factory()->create([
            'name' => 'João Silva',
            'email' => 'joao@hcp.com',
            'role' => 'employee'
        ]);

        $module = Module::factory()->create([
            'title' => 'Bem-vindo à HCP',
            'category' => 'culture',
            'points_reward' => 100,
            'estimated_duration' => 30
        ]);

        $quiz = Quiz::factory()->create([
            'module_id' => $module->id,
            'title' => 'Quiz de Boas-vindas',
            'passing_score' => 70
        ]);

        $this->createQuizQuestions($quiz, 5);

        // Act & Assert - Fluxo E2E completo

        // 1. Usuário acessa a página inicial
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Bem-vindo');

        // 2. Usuário navega para módulos
        $response = $this->actingAs($user)->get('/modules');
        $response->assertStatus(200);
        $response->assertSee($module->title);

        // 3. Usuário inicia um módulo
        $response = $this->actingAs($user)->get("/modules/{$module->id}");
        $response->assertStatus(200);
        $response->assertSee($module->title);

        // 4. Usuário atualiza progresso do módulo
        $response = $this->actingAs($user)->patch("/modules/{$module->id}/progress", [
            'progress_percentage' => 50
        ]);
        $response->assertStatus(200);

        // 5. Usuário completa o módulo
        $response = $this->actingAs($user)->post("/modules/{$module->id}/complete");
        $response->assertStatus(200);

        // 6. Usuário acessa o quiz
        $response = $this->actingAs($user)->get("/quizzes/{$quiz->id}");
        $response->assertStatus(200);
        $response->assertSee($quiz->title);

        // 7. Usuário inicia o quiz
        $response = $this->actingAs($user)->post("/quizzes/{$quiz->id}/start");
        $response->assertStatus(200);
        $attemptId = $response->json('attempt_id');

        // 8. Usuário submete respostas do quiz
        $response = $this->actingAs($user)->post("/quizzes/{$quiz->id}/submit", [
            'attempt_id' => $attemptId,
            'answers' => [
                1 => 'A', 2 => 'B', 3 => 'C', 4 => 'A', 5 => 'B'
            ]
        ]);
        $response->assertStatus(200);

        // 9. Usuário vê os resultados
        $response = $this->actingAs($user)->get("/quizzes/{$quiz->id}/results");
        $response->assertStatus(200);

        // 10. Usuário acessa dashboard
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');

        // 11. Usuário verifica progresso
        $response = $this->actingAs($user)->get('/progress');
        $response->assertStatus(200);
        $response->assertSee('100%');

        // 12. Usuário verifica gamificação
        $response = $this->actingAs($user)->get('/gamification');
        $response->assertStatus(200);
        $response->assertSee('Pontos');

        // 13. Usuário verifica certificados
        $response = $this->actingAs($user)->get('/certificates');
        $response->assertStatus(200);

        // 14. Usuário verifica notificações
        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);

        // Assert - Verificar dados no banco
        $user->refresh();
        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'module_id' => $module->id,
            'status' => 'completed'
        ]);

        $this->assertDatabaseHas('certificates', [
            'user_id' => $user->id,
            'type' => 'quiz'
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id
        ]);
    }

    /**
     * Teste E2E de autenticação e navegação
     */
    public function test_authentication_and_navigation_e2e(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@hcp.com',
            'password' => bcrypt('password123')
        ]);

        // Act & Assert

        // 1. Usuário acessa página de login
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login');

        // 2. Usuário faz login
        $response = $this->post('/login', [
            'email' => 'test@hcp.com',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/dashboard');

        // 3. Usuário acessa dashboard
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');

        // 4. Usuário navega pelo menu
        $response = $this->actingAs($user)->get('/modules');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/quizzes');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/certificates');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);

        // 5. Usuário faz logout
        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');

        // 6. Usuário não consegue acessar área protegida
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Teste E2E de gamificação e achievements
     */
    public function test_gamification_achievements_e2e(): void
    {
        // Arrange
        $user = User::factory()->create();
        $modules = Module::factory()->count(3)->create([
            'points_reward' => 100
        ]);

        $this->actingAs($user);

        // Act & Assert

        // 1. Usuário acessa página de gamificação
        $response = $this->get('/gamification');
        $response->assertStatus(200);
        $response->assertSee('Gamificação');

        // 2. Usuário completa múltiplos módulos
        foreach ($modules as $module) {
            // Iniciar módulo
            $this->post("/modules/{$module->id}/start");
            
            // Completar módulo
            $this->patch("/modules/{$module->id}/progress", [
                'progress_percentage' => 100
            ]);
            
            $this->post("/modules/{$module->id}/complete");

            // Fazer quiz
            $quiz = Quiz::factory()->create([
                'module_id' => $module->id,
                'passing_score' => 70
            ]);

            $this->createQuizQuestions($quiz, 5);

            $response = $this->post("/quizzes/{$quiz->id}/start");
            $attemptId = $response->json('attempt_id');

            $this->post("/quizzes/{$quiz->id}/submit", [
                'attempt_id' => $attemptId,
                'answers' => [
                    1 => 'A', 2 => 'B', 3 => 'C', 4 => 'A', 5 => 'B'
                ]
            ]);
        }

        // 3. Usuário verifica ranking
        $response = $this->get('/gamification/ranking');
        $response->assertStatus(200);

        // 4. Usuário verifica achievements
        $response = $this->get('/gamification/achievements');
        $response->assertStatus(200);

        // 5. Usuário verifica estatísticas
        $response = $this->get('/gamification/statistics');
        $response->assertStatus(200);

        // Assert
        $user->refresh();
        $gamification = $user->gamification;
        $this->assertEquals(300, $gamification->total_points);
    }

    /**
     * Teste E2E de responsividade mobile
     */
    public function test_mobile_responsiveness_e2e(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act & Assert

        // 1. Simular dispositivo mobile
        $this->actingAs($user)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15'
            ]);

        // 2. Acessar páginas principais
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        $response = $this->get('/modules');
        $response->assertStatus(200);

        $response = $this->get('/quizzes');
        $response->assertStatus(200);

        // 3. Verificar menu mobile
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('menu'); // Verificar se menu mobile está presente
    }

    /**
     * Teste E2E de acessibilidade
     */
    public function test_accessibility_e2e(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act & Assert

        // 1. Verificar elementos de acessibilidade
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        
        // Verificar se elementos importantes têm atributos de acessibilidade
        $response->assertSee('aria-label');
        $response->assertSee('alt=');
        $response->assertSee('role=');

        // 2. Verificar navegação por teclado
        $response = $this->actingAs($user)->get('/modules');
        $response->assertStatus(200);
        $response->assertSee('tabindex');

        // 3. Verificar contraste e cores
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        // Verificar se cores têm contraste adequado
    }

    /**
     * Teste E2E de performance
     */
    public function test_performance_e2e(): void
    {
        // Arrange
        $user = User::factory()->create();
        $startTime = microtime(true);

        // Act
        $response = $this->actingAs($user)->get('/dashboard');
        $endTime = microtime(true);

        // Assert
        $response->assertStatus(200);
        
        $loadTime = $endTime - $startTime;
        $this->assertLessThan(2.0, $loadTime, 'Dashboard deve carregar em menos de 2 segundos');

        // Testar outras páginas
        $startTime = microtime(true);
        $response = $this->actingAs($user)->get('/modules');
        $endTime = microtime(true);
        
        $loadTime = $endTime - $startTime;
        $this->assertLessThan(1.5, $loadTime, 'Módulos devem carregar em menos de 1.5 segundos');
    }

    /**
     * Helper para criar questões de quiz
     */
    private function createQuizQuestions(Quiz $quiz, int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => "Questão {$i} do quiz {$quiz->title}?",
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Opção A',
                    'B' => 'Opção B', 
                    'C' => 'Opção C',
                    'D' => 'Opção D'
                ],
                'correct_answer' => 'A',
                'points' => 20,
                'order_index' => $i
            ]);
        }
    }
} 