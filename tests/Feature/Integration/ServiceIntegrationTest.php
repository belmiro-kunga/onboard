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
use App\Models\PointsHistory;
use App\Services\GamificationService;
use App\Services\CertificateService;
use App\Services\NotificationService;
use App\Services\ActivityTrackingService;
use App\Services\PDFGenerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;
use Tests\TestCase;

class ServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Teste do fluxo completo de onboarding usando apenas services
     */
    public function test_complete_onboarding_flow_with_services(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo de Onboarding',
            'category' => 'hr',
            'points_reward' => 150
        ]);

        $quiz = Quiz::factory()->create([
            'module_id' => $module->id,
            'title' => 'Quiz de Onboarding',
            'passing_score' => 70
        ]);

        // Criar questões do quiz
        $this->createQuizQuestions($quiz, 5);

        // Mock services
        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->atLeast(3))
            ->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects($this->atLeast(3))
            ->method('dispatch');

        $pdfService = $this->createMock(PDFGenerationService::class);
        $pdfService->expects($this->once())
            ->method('generatePDF')
            ->willReturn(true);

        // Instanciar services
        $gamificationService = new GamificationService($notificationService, $dispatcher);
        $certificateService = new CertificateService($notificationService, $pdfService);
        $activityTrackingService = new ActivityTrackingService();

        // Act - Fluxo completo de onboarding

        // 1. Usuário inicia módulo
        UserProgress::create([
            'user_id' => $user->id,
            'module_id' => $module->id,
            'status' => 'in_progress',
            'progress_percentage' => 0
        ]);

        $activityTrackingService->trackActivity($user, 'module_started', [
            'module_id' => $module->id,
            'module_title' => $module->title
        ]);

        // 2. Usuário atualiza progresso
        UserProgress::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->update([
                'progress_percentage' => 50
            ]);

        $activityTrackingService->trackActivity($user, 'module_progress', [
            'module_id' => $module->id,
            'progress' => 50
        ]);

        // 3. Usuário completa módulo
        UserProgress::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->update([
                'status' => 'completed',
                'progress_percentage' => 100,
                'completed_at' => now()
            ]);

        $activityTrackingService->trackActivity($user, 'module_completed', [
            'module_id' => $module->id,
            'module_title' => $module->title
        ]);

        // 4. Adicionar pontos por completar módulo
        $gamificationService->addPoints($user, $module->points_reward, "Completou módulo: {$module->title}");

        // 5. Usuário faz quiz
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'attempt_number' => 1,
            'started_at' => now(),
            'completed_at' => now()->addMinutes(10),
            'score' => 80,
            'passed' => true,
            'total_questions' => 5,
            'correct_answers' => 4
        ]);

        $activityTrackingService->trackActivity($user, 'quiz_completed', [
            'quiz_id' => $quiz->id,
            'score' => 80,
            'passed' => true
        ]);

        // 6. Gerar certificado
        $certificate = $certificateService->generateCertificate($user, $quiz, $attempt);

        // 7. Adicionar pontos por passar no quiz
        $gamificationService->addPoints($user, 50, "Passou no quiz: {$quiz->title}");

        // Assert
        $user->refresh();
        $gamification = $user->gamification;

        // Verificar progresso do módulo
        $this->assertDatabaseHas('user_progress', [
            'user_id' => $user->id,
            'module_id' => $module->id,
            'status' => 'completed',
            'progress_percentage' => 100
        ]);

        // Verificar tentativa de quiz
        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'passed' => true,
            'score' => 80
        ]);

        // Verificar pontos acumulados
        $this->assertEquals(200, $gamification->total_points); // 150 + 50

        // Verificar certificado
        $this->assertDatabaseHas('certificates', [
            'user_id' => $user->id,
            'type' => 'quiz',
            'reference_id' => $quiz->id
        ]);

        // Verificar atividades rastreadas
        $stats = $activityTrackingService->getUserActivityStats($user);
        $this->assertEquals(4, $stats['total_activities']);

        // Verificar histórico de pontos
        $pointsHistory = PointsHistory::where('user_id', $user->id)->get();
        $this->assertCount(2, $pointsHistory);
        $this->assertEquals(150, $pointsHistory->first()->points);
        $this->assertEquals(50, $pointsHistory->last()->points);
    }

    /**
     * Teste da interação entre GamificationService e level up
     */
    public function test_gamification_level_up_integration(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->atLeast(2))
            ->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $notificationService->expects($this->once())
            ->method('sendLevelUpNotification');

        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects($this->atLeast(3))
            ->method('dispatch');

        $gamificationService = new GamificationService($notificationService, $dispatcher);

        // Act - Adicionar pontos suficientes para level up
        $gamificationService->addPoints($user, 95, 'Primeira atividade'); // Rookie (0-99)
        $gamificationService->addPoints($user, 10, 'Segunda atividade'); // Deve causar level up

        // Assert
        $user->refresh();
        $gamification = $user->gamification;

        $this->assertEquals(105, $gamification->total_points);
        $this->assertEquals('Beginner', $gamification->current_level); // 100-499

        // Verificar que level up foi registrado
        $this->assertDatabaseHas('points_histories', [
            'user_id' => $user->id,
            'points' => 95,
            'reason' => 'Primeira atividade'
        ]);

        $this->assertDatabaseHas('points_histories', [
            'user_id' => $user->id,
            'points' => 10,
            'reason' => 'Segunda atividade'
        ]);
    }

    /**
     * Teste da interação entre CertificateService e verificação
     */
    public function test_certificate_verification_integration(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo para Verificação',
            'category' => 'it'
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->once())
            ->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Certificado Gerado',
                'message' => 'Seu certificado foi gerado'
            ]));

        $pdfService = $this->createMock(PDFGenerationService::class);
        $pdfService->expects($this->once())
            ->method('generatePDF')
            ->willReturn(true);

        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act
        $certificate = $certificateService->generateModuleCertificate($user, $module);
        
        // Verificar se o certificado foi criado
        if ($certificate) {
            $verificationCode = $certificate->verification_code;

            // Verificar certificado
            $verifiedCertificate = $certificateService->verifyCertificate($verificationCode);

            // Assert
            $this->assertNotNull($verifiedCertificate);
            $this->assertEquals($certificate->id, $verifiedCertificate->id);
            $this->assertEquals($user->id, $verifiedCertificate->user_id);
            $this->assertEquals('module', $verifiedCertificate->type);
            $this->assertEquals($module->id, $verifiedCertificate->reference_id);

            // Verificar que código inválido retorna null
            $invalidCertificate = $certificateService->verifyCertificate('INVALID123');
            $this->assertNull($invalidCertificate);
        } else {
            $this->fail('Certificate was not created');
        }
    }

    /**
     * Teste da interação entre ActivityTrackingService e estatísticas
     */
    public function test_activity_tracking_statistics_integration(): void
    {
        // Arrange
        $user = User::factory()->create();
        $activityTrackingService = new ActivityTrackingService();

        // Act - Rastrear múltiplas atividades
        $activities = [
            ['action' => 'login', 'data' => ['ip' => '192.168.1.1']],
            ['action' => 'module_view', 'data' => ['module_id' => 1]],
            ['action' => 'quiz_start', 'data' => ['quiz_id' => 1]],
            ['action' => 'login', 'data' => ['ip' => '192.168.1.2']], // Login duplicado
            ['action' => 'certificate_earned', 'data' => ['certificate_id' => 1]]
        ];

        foreach ($activities as $activity) {
            $activityTrackingService->trackActivity($user, $activity['action'], $activity['data']);
        }

        // Assert
        $stats = $activityTrackingService->getUserActivityStats($user);

        $this->assertEquals(5, $stats['total_activities']);
        $this->assertNotNull($stats['last_activity']);
        $this->assertEquals('login', $stats['most_common_action']); // Login aparece 2 vezes

        // Verificar que cada atividade foi registrada
        $this->assertGreaterThan(0, $stats['total_activities']);
    }

    /**
     * Teste da interação entre NotificationService e diferentes tipos de notificação
     */
    public function test_notification_service_types_integration(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects($this->exactly(3))
            ->method('dispatch');

        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())
            ->method('to')
            ->willReturnSelf();
        $mailer->expects($this->once())
            ->method('send')
            ->willReturn(null);

        $notificationService = new NotificationService($dispatcher, $mailer);

        // Act - Criar diferentes tipos de notificação
        $notifications = [
            [
                'title' => 'Bem-vindo!',
                'message' => 'Bem-vindo ao sistema',
                'type' => 'info',
                'icon' => 'user',
                'color' => 'blue',
                'send_email' => false
            ],
            [
                'title' => 'Módulo Completo',
                'message' => 'Você completou um módulo',
                'type' => 'success',
                'icon' => 'check',
                'color' => 'green',
                'send_email' => false
            ],
            [
                'title' => 'Certificado Gerado',
                'message' => 'Seu certificado foi gerado',
                'type' => 'success',
                'icon' => 'academic-cap',
                'color' => 'yellow',
                'send_email' => true
            ]
        ];

        foreach ($notifications as $notificationData) {
            $notificationService->sendToUser(
                $user,
                $notificationData['title'],
                $notificationData['message'],
                $notificationData['type'],
                $notificationData['icon'],
                $notificationData['color'],
                null,
                [],
                $notificationData['send_email']
            );
        }

        // Assert
        $userNotifications = Notification::where('user_id', $user->id)->get();

        $this->assertCount(3, $userNotifications);

        // Verificar tipos de notificação
        $infoNotifications = $userNotifications->where('type', 'info');
        $this->assertCount(1, $infoNotifications);

        $successNotifications = $userNotifications->where('type', 'success');
        $this->assertCount(2, $successNotifications);

        // Verificar que notificações foram criadas
        $this->assertGreaterThan(0, $userNotifications->count());
    }

    /**
     * Teste da integração com tratamento de erros
     */
    public function test_service_integration_error_handling(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo com Erro',
            'points_reward' => 100
        ]);

        // Mock services que falham
        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendToUser')
            ->willThrowException(new \Exception('Notification service error'));

        $pdfService = $this->createMock(PDFGenerationService::class);
        $pdfService->method('generatePDF')
            ->willReturn(false);

        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects($this->atLeastOnce())
            ->method('dispatch');

        $gamificationService = new GamificationService($notificationService, $dispatcher);
        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act - Tentar operações que podem falhar
        $gamificationService->addPoints($user, 100, 'Test with error');
        $certificate = $certificateService->generateModuleCertificate($user, $module);

        // Assert - Verificar que operações principais ainda funcionam
        $user->refresh();
        $gamification = $user->gamification;

        // Pontos devem ser adicionados mesmo com erro de notificação
        $this->assertEquals(100, $gamification->total_points);

        // Certificado pode ser null devido a erro, mas não deve quebrar a aplicação
        if ($certificate) {
            $this->assertInstanceOf(Certificate::class, $certificate);
            $this->assertEquals($user->id, $certificate->user_id);
            $this->assertEquals('module', $certificate->type);
        }
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