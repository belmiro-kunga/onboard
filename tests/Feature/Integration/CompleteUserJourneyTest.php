<?php

declare(strict_types=1);

namespace Tests\Feature\Integration;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\UserProgress;
use App\Models\Certificate;
use App\Models\UserGamification;
use App\Models\Notification;
use App\Services\GamificationService;
use App\Services\CertificateService;
use App\Services\NotificationService;
use App\Services\ActivityTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompleteUserJourneyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Teste do fluxo completo de onboarding de um novo usuário
     */
    public function test_complete_new_user_onboarding_journey(): void
    {
        // Arrange - Criar ambiente de onboarding
        $user = User::factory()->create([
            'name' => 'João Silva',
            'email' => 'joao@hcp.com',
            'role' => 'employee',
            'department' => 'TI'
        ]);

        // Criar módulos sequenciais
        $module1 = Module::factory()->create([
            'title' => 'Bem-vindo à HCP',
            'category' => 'culture',
            'order_index' => 1,
            'points_reward' => 50,
            'estimated_duration' => 30
        ]);

        $module2 = Module::factory()->create([
            'title' => 'Políticas da Empresa',
            'category' => 'compliance',
            'order_index' => 2,
            'points_reward' => 75,
            'estimated_duration' => 45
        ]);

        $module3 = Module::factory()->create([
            'title' => 'Segurança da Informação',
            'category' => 'security',
            'order_index' => 3,
            'points_reward' => 100,
            'estimated_duration' => 60
        ]);

        // Criar quizzes para cada módulo
        $quiz1 = Quiz::factory()->create([
            'module_id' => $module1->id,
            'title' => 'Quiz - Bem-vindo',
            'passing_score' => 70
        ]);

        $quiz2 = Quiz::factory()->create([
            'module_id' => $module2->id,
            'title' => 'Quiz - Políticas',
            'passing_score' => 80
        ]);

        $quiz3 = Quiz::factory()->create([
            'module_id' => $module3->id,
            'title' => 'Quiz - Segurança',
            'passing_score' => 85
        ]);

        // Criar questões para os quizzes
        $this->createQuizQuestions($quiz1, 5);
        $this->createQuizQuestions($quiz2, 8);
        $this->createQuizQuestions($quiz3, 10);

        // Act & Assert - Fluxo completo

        // 1. Usuário inicia o primeiro módulo
        $this->actingAs($user);
        
        $response = $this->postJson("/api/modules/{$module1->id}/start");
        $response->assertStatus(200);

        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'module_id' => $module1->id,
            'status' => 'in_progress',
            'progress_percentage' => 0
        ]);

        // 2. Usuário atualiza progresso
        $response = $this->patchJson("/api/modules/{$module1->id}/progress", [
            'progress_percentage' => 50
        ]);
        $response->assertStatus(200);

        // 3. Usuário completa o módulo
        $response = $this->postJson("/api/modules/{$module1->id}/complete");
        $response->assertStatus(200);

        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'module_id' => $module1->id,
            'status' => 'completed',
            'progress_percentage' => 100
        ]);

        // 4. Usuário faz o quiz do primeiro módulo
        $response = $this->postJson("/api/quizzes/{$quiz1->id}/start");
        $response->assertStatus(200);
        $attemptId = $response->json('attempt_id');

        // 5. Usuário submete respostas do quiz
        $response = $this->postJson("/api/quizzes/{$quiz1->id}/submit", [
            'attempt_id' => $attemptId,
            'answers' => [
                1 => 'A', // Assumindo que A é a resposta correta
                2 => 'B',
                3 => 'C',
                4 => 'A',
                5 => 'B'
            ]
        ]);
        $response->assertStatus(200);

        // 6. Verificar que pontos foram adicionados
        $user->refresh();
        $this->assertDatabaseHas('user_gamifications', [
            'user_id' => $user->id,
            'total_points' => 50
        ]);

        // 7. Verificar que certificado foi gerado
        $this->assertDatabaseHas('certificates', [
            'user_id' => $user->id,
            'type' => 'quiz',
            'reference_id' => $quiz1->id
        ]);

        // 8. Verificar que notificação foi enviada
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'success'
        ]);

        // 9. Continuar com o segundo módulo
        $response = $this->postJson("/api/modules/{$module2->id}/start");
        $response->assertStatus(200);

        // 10. Completar segundo módulo rapidamente
        $response = $this->patchJson("/api/modules/{$module2->id}/progress", [
            'progress_percentage' => 100
        ]);
        $response->assertStatus(200);

        $response = $this->postJson("/api/modules/{$module2->id}/complete");
        $response->assertStatus(200);

        // 11. Fazer quiz do segundo módulo
        $response = $this->postJson("/api/quizzes/{$quiz2->id}/start");
        $attemptId2 = $response->json('attempt_id');

        $response = $this->postJson("/api/quizzes/{$quiz2->id}/submit", [
            'attempt_id' => $attemptId2,
            'answers' => [
                1 => 'A', 2 => 'B', 3 => 'C', 4 => 'A',
                5 => 'B', 6 => 'C', 7 => 'A', 8 => 'B'
            ]
        ]);
        $response->assertStatus(200);

        // 12. Verificar progresso acumulado
        $user->refresh();
        $this->assertDatabaseHas('user_gamifications', [
            'user_id' => $user->id,
            'total_points' => 125 // 50 + 75
        ]);

        // 13. Verificar que o usuário subiu de nível
        $gamification = $user->gamification;
        $this->assertEquals('Beginner', $gamification->current_level);

        // 14. Completar o terceiro módulo
        $response = $this->postJson("/api/modules/{$module3->id}/start");
        $response->assertStatus(200);

        $response = $this->patchJson("/api/modules/{$module3->id}/progress", [
            'progress_percentage' => 100
        ]);
        $response->assertStatus(200);

        $response = $this->postJson("/api/modules/{$module3->id}/complete");
        $response->assertStatus(200);

        // 15. Fazer quiz final
        $response = $this->postJson("/api/quizzes/{$quiz3->id}/start");
        $attemptId3 = $response->json('attempt_id');

        $response = $this->postJson("/api/quizzes/{$quiz3->id}/submit", [
            'attempt_id' => $attemptId3,
            'answers' => [
                1 => 'A', 2 => 'B', 3 => 'C', 4 => 'A', 5 => 'B',
                6 => 'C', 7 => 'A', 8 => 'B', 9 => 'C', 10 => 'A'
            ]
        ]);
        $response->assertStatus(200);

        // 16. Verificar conclusão completa
        $user->refresh();
        $this->assertDatabaseHas('user_gamifications', [
            'user_id' => $user->id,
            'total_points' => 225 // 50 + 75 + 100
        ]);

        // 17. Verificar certificados gerados
        $certificates = Certificate::where('user_id', $user->id)->get();
        $this->assertCount(3, $certificates);

        // 18. Verificar notificações acumuladas
        $notifications = Notification::where('user_id', $user->id)->get();
        $this->assertGreaterThanOrEqual(3, $notifications->count());

        // 19. Verificar progresso geral
        $progress = UserProgress::where('user_id', $user->id)->get();
        $this->assertCount(3, $progress);
        $this->assertTrue($progress->every(fn($p) => $p->status === 'completed'));

        // 20. Verificar ranking do usuário
        $response = $this->getJson('/api/gamification/ranking');
        $response->assertStatus(200);
        $ranking = $response->json();
        $this->assertNotEmpty($ranking);
    }

    /**
     * Teste do fluxo de gamificação com achievements
     */
    public function test_gamification_achievements_flow(): void
    {
        // Arrange
        $user = User::factory()->create();
        $modules = Module::factory()->count(5)->create([
            'points_reward' => 100
        ]);

        $this->actingAs($user);

        // Act - Completar múltiplos módulos rapidamente
        foreach ($modules as $index => $module) {
            // Iniciar módulo
            $this->postJson("/api/modules/{$module->id}/start");
            
            // Completar imediatamente
            $this->patchJson("/api/modules/{$module->id}/progress", [
                'progress_percentage' => 100
            ]);
            
            $this->postJson("/api/modules/{$module->id}/complete");

            // Fazer quiz
            $quiz = Quiz::factory()->create([
                'module_id' => $module->id,
                'passing_score' => 70
            ]);

            $this->createQuizQuestions($quiz, 5);

            $response = $this->postJson("/api/quizzes/{$quiz->id}/start");
            $attemptId = $response->json('attempt_id');

            $this->postJson("/api/quizzes/{$quiz->id}/submit", [
                'attempt_id' => $attemptId,
                'answers' => [
                    1 => 'A', 2 => 'B', 3 => 'C', 4 => 'A', 5 => 'B'
                ]
            ]);
        }

        // Assert
        $user->refresh();
        $gamification = $user->gamification;

        // Verificar pontos acumulados
        $this->assertEquals(500, $gamification->total_points);

        // Verificar nível alcançado
        $this->assertEquals('Explorer', $gamification->current_level);

        // Verificar achievements desbloqueados
        $this->assertGreaterThan(0, $gamification->achievements_count);

        // Verificar streak
        $this->assertGreaterThan(0, $gamification->streak_days);
    }

    /**
     * Teste do fluxo de certificados automáticos
     */
    public function test_automatic_certificate_generation_flow(): void
    {
        // Arrange
        $user = User::factory()->create();
        $modules = Module::factory()->count(3)->create([
            'category' => 'hr',
            'points_reward' => 100
        ]);

        $this->actingAs($user);

        // Act - Completar todos os módulos da categoria HR
        foreach ($modules as $module) {
            $this->postJson("/api/modules/{$module->id}/start");
            $this->patchJson("/api/modules/{$module->id}/progress", [
                'progress_percentage' => 100
            ]);
            $this->postJson("/api/modules/{$module->id}/complete");

            // Fazer quiz
            $quiz = Quiz::factory()->create([
                'module_id' => $module->id,
                'passing_score' => 70
            ]);

            $this->createQuizQuestions($quiz, 5);

            $response = $this->postJson("/api/quizzes/{$quiz->id}/start");
            $attemptId = $response->json('attempt_id');

            $this->postJson("/api/quizzes/{$quiz->id}/submit", [
                'attempt_id' => $attemptId,
                'answers' => [
                    1 => 'A', 2 => 'B', 3 => 'C', 4 => 'A', 5 => 'B'
                ]
            ]);
        }

        // Assert - Verificar certificado de categoria gerado
        $categoryCertificate = Certificate::where('user_id', $user->id)
            ->where('type', 'category')
            ->where('category', 'hr')
            ->first();

        $this->assertNotNull($categoryCertificate);
        $this->assertEquals('Certificado de Categoria - HR', $categoryCertificate->title);
    }

    /**
     * Teste do fluxo de notificações e atividades
     */
    public function test_notification_and_activity_tracking_flow(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo de Teste',
            'points_reward' => 150
        ]);

        $this->actingAs($user);

        // Act - Simular atividade completa
        $activities = [
            ['action' => 'login', 'data' => ['ip' => '192.168.1.1']],
            ['action' => 'module_view', 'data' => ['module_id' => $module->id]],
            ['action' => 'quiz_start', 'data' => ['quiz_id' => 1]],
            ['action' => 'certificate_earned', 'data' => ['certificate_id' => 1]],
            ['action' => 'level_up', 'data' => ['old_level' => 'Rookie', 'new_level' => 'Beginner']]
        ];

        foreach ($activities as $activity) {
            $this->postJson('/api/activities/track', $activity);
        }

        // Completar módulo para gerar notificações
        $this->postJson("/api/modules/{$module->id}/start");
        $this->patchJson("/api/modules/{$module->id}/progress", [
            'progress_percentage' => 100
        ]);
        $this->postJson("/api/modules/{$module->id}/complete");

        // Assert
        // Verificar atividades registradas
        $response = $this->getJson('/api/activities/stats');
        $response->assertStatus(200);
        $stats = $response->json();

        $this->assertGreaterThanOrEqual(5, $stats['total_activities']);
        $this->assertNotNull($stats['last_activity']);

        // Verificar notificações geradas
        $notifications = Notification::where('user_id', $user->id)->get();
        $this->assertGreaterThan(0, $notifications->count());

        // Verificar que notificações têm tipos diferentes
        $notificationTypes = $notifications->pluck('type')->unique();
        $this->assertGreaterThan(1, $notificationTypes->count());
    }

    /**
     * Teste do fluxo de recuperação de progresso
     */
    public function test_progress_recovery_flow(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo Longo',
            'estimated_duration' => 120
        ]);

        $this->actingAs($user);

        // Act - Iniciar módulo e salvar progresso parcial
        $this->postJson("/api/modules/{$module->id}/start");
        
        $this->patchJson("/api/modules/{$module->id}/progress", [
            'progress_percentage' => 30
        ]);

        // Simular logout/login
        $this->postJson('/api/auth/logout');
        $this->actingAs($user);

        // Verificar que progresso foi mantido
        $response = $this->getJson("/api/modules/{$module->id}/progress");
        $response->assertStatus(200);
        $progress = $response->json();

        $this->assertEquals(30, $progress['progress_percentage']);
        $this->assertEquals('in_progress', $progress['status']);

        // Continuar progresso
        $this->patchJson("/api/modules/{$module->id}/progress", [
            'progress_percentage' => 100
        ]);

        $this->postJson("/api/modules/{$module->id}/complete");

        // Assert
        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'module_id' => $module->id,
            'status' => 'completed',
            'progress_percentage' => 100
        ]);
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
                'points' => 10,
                'order_index' => $i
            ]);
        }
    }
} 