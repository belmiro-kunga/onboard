<?php

declare(strict_types=1);

namespace Tests\Feature\Integration;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
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

class ServiceInteractionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Teste da interação completa entre GamificationService e NotificationService
     */
    public function test_gamification_and_notification_service_interaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo de Teste',
            'points_reward' => 200
        ]);

        // Mock services
        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->exactly(3))
            ->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects($this->atLeastOnce())
            ->method('dispatch');

        $gamificationService = new GamificationService($notificationService, $dispatcher);

        // Act - Simular múltiplas adições de pontos
        $gamificationService->addPoints($user, 50, 'Primeira atividade');
        $gamificationService->addPoints($user, 75, 'Segunda atividade');
        $gamificationService->addPoints($user, 100, 'Terceira atividade');

        // Assert
        $user->refresh();
        $gamification = $user->gamification;

        $this->assertEquals(225, $gamification->total_points);
        $this->assertEquals('Beginner', $gamification->current_level);

        // Verificar histórico de pontos
        $pointsHistory = PointsHistory::where('user_id', $user->id)->get();
        $this->assertCount(3, $pointsHistory);

        // Verificar notificações
        $notifications = Notification::where('user_id', $user->id)->get();
        $this->assertCount(3, $notifications);
    }

    /**
     * Teste da interação entre CertificateService e PDFGenerationService
     */
    public function test_certificate_and_pdf_service_interaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo para Certificado',
            'category' => 'hr'
        ]);

        // Mock services
        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->once())
            ->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Certificado Gerado',
                'message' => 'Seu certificado foi gerado com sucesso'
            ]));

        $pdfService = $this->createMock(PDFGenerationService::class);
        $pdfService->expects($this->once())
            ->method('generatePDF')
            ->willReturn(true);

        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act
        $certificate = $certificateService->generateModuleCertificate($user, $module);

        // Assert
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($user->id, $certificate->user_id);
        $this->assertEquals('module', $certificate->type);
        $this->assertEquals($module->id, $certificate->reference_id);

        // Verificar que o certificado tem dados válidos
        $this->assertNotEmpty($certificate->verification_code);
        $this->assertNotEmpty($certificate->title);
        $this->assertNotNull($certificate->issued_at);
    }

    /**
     * Teste da interação entre ActivityTrackingService e outros services
     */
    public function test_activity_tracking_service_interaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        $activityTrackingService = new ActivityTrackingService();

        // Act - Rastrear múltiplas atividades
        $activities = [
            ['action' => 'login', 'data' => ['ip' => '192.168.1.1', 'user_agent' => 'Mozilla/5.0']],
            ['action' => 'module_start', 'data' => ['module_id' => 1, 'module_title' => 'Test Module']],
            ['action' => 'quiz_complete', 'data' => ['quiz_id' => 1, 'score' => 85]],
            ['action' => 'certificate_earned', 'data' => ['certificate_id' => 1, 'type' => 'module']],
            ['action' => 'level_up', 'data' => ['old_level' => 'Rookie', 'new_level' => 'Beginner']]
        ];

        foreach ($activities as $activity) {
            $activityTrackingService->trackActivity($user, $activity['action'], $activity['data']);
        }

        // Assert
        $stats = $activityTrackingService->getUserActivityStats($user);

        $this->assertEquals(5, $stats['total_activities']);
        $this->assertNotNull($stats['last_activity']);
        $this->assertEquals('login', $stats['most_common_action']);

        // Verificar que atividades foram registradas com dados corretos
        $loginActivity = $activityTrackingService->getUserActivities($user, 'login')->first();
        $this->assertNotNull($loginActivity);
        $this->assertEquals('192.168.1.1', $loginActivity->data['ip']);
    }

    /**
     * Teste da interação entre NotificationService e Mailer
     */
    public function test_notification_service_and_mailer_interaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects($this->once())
            ->method('dispatch');

        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())
            ->method('to')
            ->willReturnSelf();
        $mailer->expects($this->once())
            ->method('send')
            ->willReturn(null);

        $notificationService = new NotificationService($dispatcher, $mailer);

        // Act
        $notification = $notificationService->sendToUser(
            $user,
            'Teste de Notificação',
            'Esta é uma notificação de teste',
            'info',
            'bell',
            'blue',
            'https://example.com',
            ['test_data' => 'value'],
            true // Enviar email
        );

        // Assert
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals($user->id, $notification->user_id);
        $this->assertEquals('Teste de Notificação', $notification->title);
        $this->assertEquals('Esta é uma notificação de teste', $notification->message);
        $this->assertEquals('info', $notification->type);
    }

    /**
     * Teste da interação entre múltiplos services em um fluxo complexo
     */
    public function test_complex_multi_service_interaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo Complexo',
            'points_reward' => 300
        ]);

        $quiz = Quiz::factory()->create([
            'module_id' => $module->id,
            'title' => 'Quiz Complexo',
            'passing_score' => 80
        ]);

        // Criar questões
        $this->createQuizQuestions($quiz, 10);

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

        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->atLeastOnce())
            ->method('to')
            ->willReturnSelf();
        $mailer->expects($this->atLeastOnce())
            ->method('send')
            ->willReturn(null);

        // Instanciar services
        $gamificationService = new GamificationService($notificationService, $dispatcher);
        $certificateService = new CertificateService($notificationService, $pdfService);
        $activityTrackingService = new ActivityTrackingService();
        $notificationServiceWithMailer = new NotificationService($dispatcher, $mailer);

        // Act - Fluxo complexo
        // 1. Rastrear início da atividade
        $activityTrackingService->trackActivity($user, 'module_start', [
            'module_id' => $module->id,
            'module_title' => $module->title
        ]);

        // 2. Adicionar pontos por completar módulo
        $gamificationService->addPoints($user, $module->points_reward, "Completou módulo: {$module->title}");

        // 3. Gerar certificado
        $certificate = $certificateService->generateModuleCertificate($user, $module);

        // 4. Enviar notificação por email
        $notificationServiceWithMailer->sendToUser(
            $user,
            'Certificado Gerado!',
            "Parabéns! Você completou o módulo {$module->title}",
            'success',
            'academic-cap',
            'green',
            'https://example.com/certificate',
            ['certificate_id' => $certificate->id],
            true
        );

        // 5. Rastrear conclusão
        $activityTrackingService->trackActivity($user, 'module_complete', [
            'module_id' => $module->id,
            'certificate_id' => $certificate->id,
            'points_earned' => $module->points_reward
        ]);

        // Assert
        $user->refresh();
        $gamification = $user->gamification;

        // Verificar pontos
        $this->assertEquals(300, $gamification->total_points);

        // Verificar certificado
        $this->assertDatabaseHas('certificates', [
            'user_id' => $user->id,
            'type' => 'module',
            'reference_id' => $module->id
        ]);

        // Verificar atividades
        $stats = $activityTrackingService->getUserActivityStats($user);
        $this->assertEquals(2, $stats['total_activities']);

        // Verificar notificações
        $notifications = Notification::where('user_id', $user->id)->get();
        $this->assertGreaterThanOrEqual(3, $notifications->count());

        // Verificar histórico de pontos
        $pointsHistory = PointsHistory::where('user_id', $user->id)->get();
        $this->assertCount(1, $pointsHistory);
        $this->assertEquals(300, $pointsHistory->first()->points);
    }

    /**
     * Teste da interação com tratamento de erros
     */
    public function test_service_interaction_with_error_handling(): void
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
            ->willReturn(false); // PDF generation fails

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

        // Certificado deve ser criado mesmo com erro de PDF
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($user->id, $certificate->user_id);
    }

    /**
     * Teste da interação com dados de alta concorrência
     */
    public function test_service_interaction_with_high_concurrency(): void
    {
        // Arrange
        $users = User::factory()->count(10)->create();
        $module = Module::factory()->create([
            'points_reward' => 50
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => 1,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $dispatcher = $this->createMock(Dispatcher::class);
        $dispatcher->expects($this->atLeast(10))
            ->method('dispatch');

        $gamificationService = new GamificationService($notificationService, $dispatcher);

        // Act - Simular múltiplos usuários adicionando pontos simultaneamente
        foreach ($users as $user) {
            $gamificationService->addPoints($user, 50, 'Concurrent test');
        }

        // Assert
        $totalPoints = UserGamification::sum('total_points');
        $this->assertEquals(500, $totalPoints); // 10 users * 50 points

        $totalNotifications = Notification::count();
        $this->assertEquals(10, $totalNotifications);

        // Verificar que cada usuário tem seus próprios dados
        foreach ($users as $user) {
            $user->refresh();
            $this->assertEquals(50, $user->gamification->total_points);
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
                'points' => 10,
                'order_index' => $i
            ]);
        }
    }
} 