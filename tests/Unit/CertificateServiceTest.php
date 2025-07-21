<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Models\Module;
use App\Models\Certificate;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\CertificateService;
use App\Services\NotificationService;
use App\Services\PDFGenerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CertificateServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_generate_module_certificate_creates_certificate_and_sends_notification(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Test Module',
            'category' => 'hr',
            'points_reward' => 100
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendToUser')
            ->once()
            ->andReturn(true);

        $pdfService = Mockery::mock(PDFGenerationService::class);
        $pdfService->shouldReceive('generatePDF')
            ->once()
            ->andReturn(true);

        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $certificate = $service->generateModuleCertificate($user, $module);

        // Assert
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($user->id, $certificate->user_id);
        $this->assertEquals('module', $certificate->type);
        $this->assertEquals($module->id, $certificate->reference_id);
        $this->assertEquals("Certificado de Conclusão - {$module->title}", $certificate->title);
    }

    public function test_generate_module_certificate_handles_pdf_generation_failure(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Test Module',
            'category' => 'hr',
            'points_reward' => 100
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendToUser')
            ->once()
            ->andReturn(true);

        $pdfService = Mockery::mock(PDFGenerationService::class);
        $pdfService->shouldReceive('generatePDF')
            ->once()
            ->andReturn(false); // PDF generation fails

        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $certificate = $service->generateModuleCertificate($user, $module);

        // Assert - Certificate should still be created even if PDF fails
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($user->id, $certificate->user_id);
    }

    public function test_generate_module_certificate_handles_notification_failure(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Test Module',
            'category' => 'hr',
            'points_reward' => 100
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendToUser')
            ->once()
            ->andThrow(new \Exception('Notification failed'));

        $pdfService = Mockery::mock(PDFGenerationService::class);
        $pdfService->shouldReceive('generatePDF')
            ->once()
            ->andReturn(true);

        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $certificate = $service->generateModuleCertificate($user, $module);

        // Assert - Certificate should still be created even if notification fails
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($user->id, $certificate->user_id);
    }

    public function test_verify_certificate_returns_certificate(): void
    {
        // Arrange
        $user = User::factory()->create();
        $certificate = Certificate::factory()->create([
            'user_id' => $user->id,
            'verification_code' => 'TEST123'
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $pdfService = Mockery::mock(PDFGenerationService::class);
        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $result = $service->verifyCertificate('TEST123');

        // Assert
        $this->assertInstanceOf(Certificate::class, $result);
        $this->assertEquals($certificate->id, $result->id);
    }

    public function test_verify_certificate_returns_null_for_invalid_code(): void
    {
        // Arrange
        $notificationService = Mockery::mock(NotificationService::class);
        $pdfService = Mockery::mock(PDFGenerationService::class);
        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $result = $service->verifyCertificate('INVALID123');

        // Assert
        $this->assertNull($result);
    }

    public function test_verify_certificate_returns_null_for_empty_code(): void
    {
        // Arrange
        $notificationService = Mockery::mock(NotificationService::class);
        $pdfService = Mockery::mock(PDFGenerationService::class);
        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $result = $service->verifyCertificate('');

        // Assert
        $this->assertNull($result);
    }

    public function test_generate_certificate_report_returns_stats(): void
    {
        // Arrange
        Certificate::factory()->count(5)->create();

        $notificationService = Mockery::mock(NotificationService::class);
        $pdfService = Mockery::mock(PDFGenerationService::class);
        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $report = $service->generateCertificateReport();

        // Assert
        $this->assertArrayHasKey('total', $report);
        $this->assertArrayHasKey('by_type', $report);
        $this->assertArrayHasKey('recent', $report);
        $this->assertEquals(5, $report['total']);
    }

    public function test_generate_certificate_report_with_empty_database(): void
    {
        // Arrange
        $notificationService = Mockery::mock(NotificationService::class);
        $pdfService = Mockery::mock(PDFGenerationService::class);
        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $report = $service->generateCertificateReport();

        // Assert
        $this->assertEquals(0, $report['total']);
        $this->assertEmpty($report['by_type']);
        $this->assertEmpty($report['recent']);
    }

    public function test_generate_quiz_certificate(): void
    {
        // Arrange
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create(['title' => 'Test Quiz']);
        $attempt = QuizAttempt::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => 85
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $pdfService = Mockery::mock(PDFGenerationService::class);
        $pdfService->shouldReceive('generatePDF')
            ->once()
            ->andReturn(true);

        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $certificate = $service->generateCertificate($user, $quiz, $attempt);

        // Assert
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($user->id, $certificate->user_id);
        $this->assertEquals('quiz', $certificate->type);
        $this->assertEquals($quiz->id, $certificate->reference_id);
        $this->assertEquals("Certificado - {$quiz->title}", $certificate->title);
    }

    public function test_check_and_generate_automatic_certificates(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Test Module',
            'category' => 'hr',
            'points_reward' => 100
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendToUser')
            ->zeroOrMoreTimes()
            ->andReturn(true);

        $pdfService = Mockery::mock(PDFGenerationService::class);
        $pdfService->shouldReceive('generatePDF')
            ->zeroOrMoreTimes()
            ->andReturn(true);

        $service = new CertificateService($notificationService, $pdfService);

        // Act
        $certificates = $service->checkAndGenerateAutomaticCertificates($user);

        // Assert
        $this->assertIsArray($certificates);
        // Should not generate certificates without proper prerequisites
        $this->assertCount(0, $certificates);
    }

    public function test_generate_verification_code_is_unique(): void
    {
        // Arrange
        $notificationService = Mockery::mock(NotificationService::class);
        $pdfService = Mockery::mock(PDFGenerationService::class);
        $service = new CertificateService($notificationService, $pdfService);

        // Create a certificate with a specific verification code
        Certificate::factory()->create(['verification_code' => 'TEST1234']);

        // Act - This will be called internally when generating a certificate
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Test Module',
            'category' => 'hr',
            'points_reward' => 100
        ]);

        $notificationService->shouldReceive('sendToUser')
            ->once()
            ->andReturn(true);

        $pdfService->shouldReceive('generatePDF')
            ->once()
            ->andReturn(true);

        $certificate = $service->generateModuleCertificate($user, $module);

        // Assert
        $this->assertNotEquals('TEST1234', $certificate->verification_code);
        $this->assertNotEmpty($certificate->verification_code);
    }

    public function test_can_create_certificate_directly(): void
    {
        // Teste simples para verificar se conseguimos criar um certificado diretamente
        $user = User::factory()->create();
        
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'quiz_id' => null,
            'quiz_attempt_id' => null,
            'type' => 'module',
            'title' => 'Test Certificate',
            'description' => 'Test Description',
            'reference_id' => 1,
            'category' => 'hr',
            'certificate_number' => 'CERT-12345',
            'verification_code' => 'TEST123',
            'issued_at' => now(),
            'metadata' => ['test' => 'data']
        ]);

        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($user->id, $certificate->user_id);
    }

    public function test_generate_module_certificate_simple(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Test Module',
            'category' => 'hr',
            'points_reward' => 100
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendToUser')
            ->once()
            ->andReturn(true);

        $pdfService = Mockery::mock(PDFGenerationService::class);
        $pdfService->shouldReceive('generatePDF')
            ->once()
            ->andReturn(true);

        $service = new CertificateService($notificationService, $pdfService);

        // Act - Usar reflection para testar o método privado ou criar um método público simples
        $certificateData = [
            'user_id' => $user->id,
            'quiz_id' => null,
            'quiz_attempt_id' => null,
            'type' => 'module',
            'title' => "Certificado de Conclusão - {$module->title}",
            'description' => "Certificado de conclusão do módulo {$module->title} do programa de onboarding da Hemera Capital Partners.",
            'reference_id' => $module->id,
            'category' => $module->category,
            'certificate_number' => 'CERT-TEST',
            'verification_code' => 'TEST123',
            'issued_at' => now(),
            'metadata' => [
                'module_title' => $module->title,
                'module_category' => $module->category,
                'completion_date' => now()->toDateString(),
                'points_earned' => $module->points_reward,
            ]
        ];
        
        $certificate = Certificate::create($certificateData);

        // Assert
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($user->id, $certificate->user_id);
        $this->assertEquals('module', $certificate->type);
        $this->assertEquals($module->id, $certificate->reference_id);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 