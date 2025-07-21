<?php

declare(strict_types=1);

namespace Tests\Contract;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Certificate;
use App\Models\Notification;
use App\Services\GamificationService;
use App\Services\CertificateService;
use App\Services\NotificationService;
use App\Services\ActivityTrackingService;
use App\Services\PDFGenerationService;
use App\Contracts\GamificationServiceInterface;
use App\Contracts\CertificateServiceInterface;
use App\Contracts\NotificationServiceInterface;
use App\Contracts\ActivityTrackingServiceInterface;
use App\Contracts\PDFGenerationServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;
use Tests\TestCase;

class ServiceContractTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Teste de contrato para GamificationService
     */
    public function test_gamification_service_contract(): void
    {
        // Arrange
        $user = User::factory()->create();
        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $dispatcher = $this->createMock(Dispatcher::class);

        $gamificationService = new GamificationService($notificationService, $dispatcher);

        // Act & Assert - Verificar métodos obrigatórios
        $this->assertTrue(method_exists($gamificationService, 'addPoints'));
        $this->assertTrue(method_exists($gamificationService, 'removePoints'));
        $this->assertTrue(method_exists($gamificationService, 'getUserLevel'));
        $this->assertTrue(method_exists($gamificationService, 'calculateLevel'));
        $this->assertTrue(method_exists($gamificationService, 'checkLevelUp'));

        // Verificar tipos de retorno
        $result = $gamificationService->addPoints($user, 100, 'Test');
        $this->assertIsBool($result);

        $level = $gamificationService->getUserLevel($user);
        $this->assertIsString($level);

        $calculatedLevel = $gamificationService->calculateLevel(100);
        $this->assertIsString($calculatedLevel);

        $levelUp = $gamificationService->checkLevelUp($user, 100);
        $this->assertIsBool($levelUp);
    }

    /**
     * Teste de contrato para CertificateService
     */
    public function test_certificate_service_contract(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create();
        $quiz = Quiz::factory()->create(['module_id' => $module->id]);

        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $pdfService = $this->createMock(PDFGenerationServiceInterface::class);

        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act & Assert - Verificar métodos obrigatórios
        $this->assertTrue(method_exists($certificateService, 'generateModuleCertificate'));
        $this->assertTrue(method_exists($certificateService, 'generateQuizCertificate'));
        $this->assertTrue(method_exists($certificateService, 'verifyCertificate'));
        $this->assertTrue(method_exists($certificateService, 'revokeCertificate'));
        $this->assertTrue(method_exists($certificateService, 'getUserCertificates'));

        // Verificar tipos de retorno
        $certificate = $certificateService->generateModuleCertificate($user, $module);
        $this->assertInstanceOf(Certificate::class, $certificate);

        $verified = $certificateService->verifyCertificate($certificate->verification_code);
        $this->assertInstanceOf(Certificate::class, $verified);

        $revoked = $certificateService->revokeCertificate($certificate->id);
        $this->assertIsBool($revoked);

        $certificates = $certificateService->getUserCertificates($user);
        $this->assertIsArray($certificates);
    }

    /**
     * Teste de contrato para NotificationService
     */
    public function test_notification_service_contract(): void
    {
        // Arrange
        $user = User::factory()->create();
        $dispatcher = $this->createMock(Dispatcher::class);
        $mailer = $this->createMock(Mailer::class);

        $notificationService = new NotificationService($dispatcher, $mailer);

        // Act & Assert - Verificar métodos obrigatórios
        $this->assertTrue(method_exists($notificationService, 'sendToUser'));
        $this->assertTrue(method_exists($notificationService, 'sendToAllUsers'));
        $this->assertTrue(method_exists($notificationService, 'markAsRead'));
        $this->assertTrue(method_exists($notificationService, 'getUnreadCount'));
        $this->assertTrue(method_exists($notificationService, 'deleteNotification'));

        // Verificar tipos de retorno
        $notification = $notificationService->sendToUser(
            $user,
            'Test',
            'Test message',
            'info'
        );
        $this->assertInstanceOf(Notification::class, $notification);

        $count = $notificationService->getUnreadCount($user);
        $this->assertIsInt($count);

        $marked = $notificationService->markAsRead($notification->id);
        $this->assertIsBool($marked);

        $deleted = $notificationService->deleteNotification($notification->id);
        $this->assertIsBool($deleted);
    }

    /**
     * Teste de contrato para ActivityTrackingService
     */
    public function test_activity_tracking_service_contract(): void
    {
        // Arrange
        $user = User::factory()->create();
        $activityTrackingService = new ActivityTrackingService();

        // Act & Assert - Verificar métodos obrigatórios
        $this->assertTrue(method_exists($activityTrackingService, 'trackActivity'));
        $this->assertTrue(method_exists($activityTrackingService, 'getUserActivityStats'));
        $this->assertTrue(method_exists($activityTrackingService, 'getRecentActivities'));
        $this->assertTrue(method_exists($activityTrackingService, 'clearOldActivities'));
        $this->assertTrue(method_exists($activityTrackingService, 'exportUserActivities'));

        // Verificar tipos de retorno
        $tracked = $activityTrackingService->trackActivity($user, 'test', ['data' => 'value']);
        $this->assertIsBool($tracked);

        $stats = $activityTrackingService->getUserActivityStats($user);
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_activities', $stats);
        $this->assertArrayHasKey('last_activity', $stats);

        $activities = $activityTrackingService->getRecentActivities($user, 10);
        $this->assertIsArray($activities);

        $cleared = $activityTrackingService->clearOldActivities(30);
        $this->assertIsInt($cleared);

        $exported = $activityTrackingService->exportUserActivities($user);
        $this->assertIsString($exported);
    }

    /**
     * Teste de contrato para PDFGenerationService
     */
    public function test_pdf_generation_service_contract(): void
    {
        // Arrange
        $pdfService = new PDFGenerationService();

        // Act & Assert - Verificar métodos obrigatórios
        $this->assertTrue(method_exists($pdfService, 'generatePDF'));
        $this->assertTrue(method_exists($pdfService, 'generateCertificatePDF'));
        $this->assertTrue(method_exists($pdfService, 'generateReportPDF'));
        $this->assertTrue(method_exists($pdfService, 'validatePDF'));
        $this->assertTrue(method_exists($pdfService, 'getPDFSize'));

        // Verificar tipos de retorno
        $generated = $pdfService->generatePDF('test', []);
        $this->assertIsBool($generated);

        $certificatePDF = $pdfService->generateCertificatePDF(new Certificate());
        $this->assertIsBool($certificatePDF);

        $reportPDF = $pdfService->generateReportPDF('test', []);
        $this->assertIsBool($reportPDF);

        $valid = $pdfService->validatePDF('test.pdf');
        $this->assertIsBool($valid);

        $size = $pdfService->getPDFSize('test.pdf');
        $this->assertIsInt($size);
    }

    /**
     * Teste de contrato para interfaces de service
     */
    public function test_service_interfaces_contract(): void
    {
        // Verificar que as interfaces existem
        $this->assertTrue(interface_exists(GamificationServiceInterface::class));
        $this->assertTrue(interface_exists(CertificateServiceInterface::class));
        $this->assertTrue(interface_exists(NotificationServiceInterface::class));
        $this->assertTrue(interface_exists(ActivityTrackingServiceInterface::class));
        $this->assertTrue(interface_exists(PDFGenerationServiceInterface::class));

        // Verificar que os services implementam as interfaces
        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $dispatcher = $this->createMock(Dispatcher::class);
        $pdfService = $this->createMock(PDFGenerationServiceInterface::class);

        $gamificationService = new GamificationService($notificationService, $dispatcher);
        $certificateService = new CertificateService($notificationService, $pdfService);
        $activityTrackingService = new ActivityTrackingService();

        $this->assertInstanceOf(GamificationServiceInterface::class, $gamificationService);
        $this->assertInstanceOf(CertificateServiceInterface::class, $certificateService);
        $this->assertInstanceOf(ActivityTrackingServiceInterface::class, $activityTrackingService);
    }

    /**
     * Teste de contrato para dados de entrada
     */
    public function test_input_data_contract(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create();

        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $dispatcher = $this->createMock(Dispatcher::class);
        $pdfService = $this->createMock(PDFGenerationServiceInterface::class);

        $gamificationService = new GamificationService($notificationService, $dispatcher);
        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act & Assert - Verificar validação de entrada

        // GamificationService deve aceitar pontos positivos
        $result = $gamificationService->addPoints($user, 100, 'Valid points');
        $this->assertTrue($result);

        // GamificationService deve rejeitar pontos negativos
        $result = $gamificationService->addPoints($user, -50, 'Invalid points');
        $this->assertFalse($result);

        // CertificateService deve aceitar dados válidos
        $certificate = $certificateService->generateModuleCertificate($user, $module);
        $this->assertInstanceOf(Certificate::class, $certificate);

        // CertificateService deve rejeitar dados inválidos
        $this->expectException(\InvalidArgumentException::class);
        $certificateService->generateModuleCertificate(null, $module);
    }

    /**
     * Teste de contrato para dados de saída
     */
    public function test_output_data_contract(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create();

        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $notificationService->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $dispatcher = $this->createMock(Dispatcher::class);
        $pdfService = $this->createMock(PDFGenerationServiceInterface::class);
        $pdfService->method('generatePDF')->willReturn(true);

        $gamificationService = new GamificationService($notificationService, $dispatcher);
        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act & Assert - Verificar estrutura de dados de saída

        // GamificationService output
        $gamificationService->addPoints($user, 100, 'Test');
        $user->refresh();
        $gamification = $user->gamification;

        $this->assertIsInt($gamification->total_points);
        $this->assertIsString($gamification->current_level);
        $this->assertIsInt($gamification->streak_days);
        $this->assertGreaterThanOrEqual(0, $gamification->total_points);

        // CertificateService output
        $certificate = $certificateService->generateModuleCertificate($user, $module);

        $this->assertIsString($certificate->title);
        $this->assertIsString($certificate->verification_code);
        $this->assertIsString($certificate->type);
        $this->assertIsArray($certificate->metadata);
        $this->assertNotNull($certificate->issued_at);

        // NotificationService output
        $notification = $notificationService->sendToUser(
            $user,
            'Test',
            'Test message',
            'info'
        );

        $this->assertIsString($notification->title);
        $this->assertIsString($notification->message);
        $this->assertIsString($notification->type);
        $this->assertIsString($notification->icon);
        $this->assertIsString($notification->color);
    }

    /**
     * Teste de contrato para tratamento de erros
     */
    public function test_error_handling_contract(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create();

        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $notificationService->method('sendToUser')
            ->willThrowException(new \Exception('Service error'));

        $dispatcher = $this->createMock(Dispatcher::class);
        $pdfService = $this->createMock(PDFGenerationServiceInterface::class);
        $pdfService->method('generatePDF')->willReturn(false);

        $gamificationService = new GamificationService($notificationService, $dispatcher);
        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act & Assert - Verificar tratamento de erros

        // GamificationService deve lidar com erros de notificação
        $result = $gamificationService->addPoints($user, 100, 'Test with error');
        $this->assertIsBool($result);

        // CertificateService deve lidar com erros de PDF
        $certificate = $certificateService->generateModuleCertificate($user, $module);
        $this->assertInstanceOf(Certificate::class, $certificate);

        // Verificar que operações principais ainda funcionam
        $user->refresh();
        $this->assertEquals(100, $user->gamification->total_points);
    }

    /**
     * Teste de contrato para performance
     */
    public function test_performance_contract(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create();

        $notificationService = $this->createMock(NotificationServiceInterface::class);
        $notificationService->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $dispatcher = $this->createMock(Dispatcher::class);
        $pdfService = $this->createMock(PDFGenerationServiceInterface::class);
        $pdfService->method('generatePDF')->willReturn(true);

        $gamificationService = new GamificationService($notificationService, $dispatcher);
        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act & Assert - Verificar limites de performance

        // GamificationService deve ser rápido
        $startTime = microtime(true);
        $gamificationService->addPoints($user, 100, 'Performance test');
        $endTime = microtime(true);

        $executionTime = $endTime - $startTime;
        $this->assertLessThan(0.1, $executionTime, 'GamificationService deve executar em menos de 0.1 segundos');

        // CertificateService deve ser rápido
        $startTime = microtime(true);
        $certificateService->generateModuleCertificate($user, $module);
        $endTime = microtime(true);

        $executionTime = $endTime - $startTime;
        $this->assertLessThan(0.5, $executionTime, 'CertificateService deve executar em menos de 0.5 segundos');
    }
} 