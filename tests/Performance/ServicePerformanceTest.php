<?php

declare(strict_types=1);

namespace Tests\Performance;

use App\Models\User;
use App\Models\Module;
use App\Models\Certificate;
use App\Models\UserGamification;
use App\Services\GamificationService;
use App\Services\CertificateService;
use App\Services\NotificationService;
use App\Services\ActivityTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicePerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_gamification_service_performance_with_large_dataset(): void
    {
        // Arrange
        $users = User::factory()->count(100)->create();
        $modules = Module::factory()->count(20)->create(['points_reward' => 50]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendPointsNotification')
            ->willReturn(null);

        $gamificationService = new GamificationService($notificationService, app('events'));

        $startTime = microtime(true);

        // Act - Add points to all users for all modules
        foreach ($users as $user) {
            foreach ($modules as $module) {
                $gamificationService->addPoints($user, $module->points_reward, "Module: {$module->title}");
            }
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertLessThan(10.0, $executionTime, 'Gamification operations took too long');
        
        // Verify data integrity
        $totalPoints = UserGamification::sum('total_points');
        $expectedPoints = 100 * 20 * 50; // users * modules * points
        $this->assertEquals($expectedPoints, $totalPoints);
    }

    public function test_certificate_service_performance_with_bulk_generation(): void
    {
        // Arrange
        $users = User::factory()->count(50)->create();
        $modules = Module::factory()->count(10)->create(['category' => 'hr']);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendToUser')
            ->willReturn(new \App\Models\Notification());

        $pdfService = $this->createMock(\App\Services\PDFGenerationService::class);
        $pdfService->method('generatePDF')
            ->willReturn(true);

        $certificateService = new CertificateService($notificationService, $pdfService);

        $startTime = microtime(true);

        // Act - Generate certificates for all users and modules
        $certificatesGenerated = 0;
        foreach ($users as $user) {
            foreach ($modules as $module) {
                $certificate = $certificateService->generateModuleCertificate($user, $module);
                if ($certificate) {
                    $certificatesGenerated++;
                }
            }
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertLessThan(15.0, $executionTime, 'Certificate generation took too long');
        $this->assertEquals(500, $certificatesGenerated); // 50 users * 10 modules
    }

    public function test_notification_service_performance_with_bulk_sending(): void
    {
        // Arrange
        $users = User::factory()->count(1000)->create();
        $userIds = $users->pluck('id')->toArray();

        $notificationService = new NotificationService(app('events'), app('mailer'));

        $startTime = microtime(true);

        // Act - Send notification to all users
        $sentCount = $notificationService->sendToMultipleUsers(
            $userIds,
            'Bulk Test',
            'This is a bulk notification test',
            'info'
        );

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertLessThan(30.0, $executionTime, 'Bulk notification sending took too long');
        $this->assertEquals(1000, $sentCount);
    }

    public function test_activity_tracking_service_performance(): void
    {
        // Arrange
        $user = User::factory()->create();
        $activityTrackingService = new ActivityTrackingService();

        $startTime = microtime(true);

        // Act - Track many activities
        for ($i = 0; $i < 1000; $i++) {
            $activityTrackingService->trackActivity($user, "activity_{$i}", [
                'iteration' => $i,
                'timestamp' => now()
            ]);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertLessThan(5.0, $executionTime, 'Activity tracking took too long');
        
        // Verify activities were tracked
        $stats = $activityTrackingService->getUserActivityStats($user);
        $this->assertGreaterThan(0, $stats['total_activities']);
    }

    public function test_database_query_performance_for_ranking(): void
    {
        // Arrange
        UserGamification::factory()->count(1000)->create();

        $notificationService = $this->createMock(NotificationService::class);
        $gamificationService = new GamificationService($notificationService, app('events'));

        $startTime = microtime(true);

        // Act - Get global ranking
        $ranking = $gamificationService->getGlobalRanking(100);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertLessThan(1.0, $executionTime, 'Ranking query took too long');
        $this->assertCount(100, $ranking);
    }

    public function test_certificate_verification_performance(): void
    {
        // Arrange
        $certificates = Certificate::factory()->count(1000)->create();
        $verificationCodes = $certificates->pluck('verification_code')->toArray();

        $notificationService = $this->createMock(NotificationService::class);
        $certificateService = new CertificateService($notificationService, app('App\Services\PDFGenerationService'));

        $startTime = microtime(true);

        // Act - Verify multiple certificates
        $verifiedCount = 0;
        foreach ($verificationCodes as $code) {
            $certificate = $certificateService->verifyCertificate($code);
            if ($certificate) {
                $verifiedCount++;
            }
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertLessThan(2.0, $executionTime, 'Certificate verification took too long');
        $this->assertEquals(1000, $verifiedCount);
    }

    public function test_memory_usage_for_large_operations(): void
    {
        // Arrange
        $initialMemory = memory_get_usage();
        
        $users = User::factory()->count(500)->create();
        $modules = Module::factory()->count(50)->create();

        $notificationService = $this->createMock(NotificationService::class);
        $gamificationService = new GamificationService($notificationService, app('events'));

        // Act - Perform large operation
        foreach ($users as $user) {
            foreach ($modules as $module) {
                $gamificationService->addPoints($user, 10, "Test");
            }
        }

        $finalMemory = memory_get_usage();
        $memoryIncrease = $finalMemory - $initialMemory;

        // Assert - Memory increase should be reasonable (less than 100MB)
        $this->assertLessThan(100 * 1024 * 1024, $memoryIncrease, 'Memory usage too high');
    }

    public function test_concurrent_operations_performance(): void
    {
        // Arrange
        $user = User::factory()->create();
        $modules = Module::factory()->count(10)->create(['points_reward' => 10]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendPointsNotification')
            ->willReturn(null);

        $gamificationService = new GamificationService($notificationService, app('events'));

        $startTime = microtime(true);

        // Act - Simulate concurrent operations
        $promises = [];
        foreach ($modules as $module) {
            // Simulate concurrent point additions
            $gamificationService->addPoints($user, $module->points_reward, "Concurrent: {$module->title}");
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Assert
        $this->assertLessThan(5.0, $executionTime, 'Concurrent operations took too long');
        
        $user->refresh();
        $this->assertEquals(100, $user->gamification->total_points); // 10 modules * 10 points
    }
} 