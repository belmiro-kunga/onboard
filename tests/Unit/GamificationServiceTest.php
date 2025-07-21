<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserGamification;
use App\Models\PointsHistory;
use App\Services\GamificationService;
use App\Services\NotificationService;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GamificationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_add_points_increments_total_points_and_triggers_notification(): void
    {
        // Arrange
        $user = User::factory()->create();
        $gamification = UserGamification::factory()->create([
            'user_id' => $user->id, 
            'total_points' => 0,
            'current_level' => 'Rookie'
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendPointsNotification')
            ->once()
            ->with($user, 50, 'Test Reason');
        $notificationService->shouldReceive('sendLevelUpNotification')
            ->zeroOrMoreTimes();

        $dispatcher = Mockery::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->atLeast()
            ->once();

        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $service->addPoints($user, 50, 'Test Reason');

        // Assert
        $gamification->refresh();
        $this->assertEquals(50, $gamification->total_points);
    }

    public function test_add_points_creates_gamification_if_not_exists(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendPointsNotification')
            ->once();
        $notificationService->shouldReceive('sendLevelUpNotification')
            ->zeroOrMoreTimes();

        $dispatcher = Mockery::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->atLeast()
            ->once();

        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $service->addPoints($user, 100, 'First Points');

        // Assert
        $this->assertDatabaseHas('user_gamifications', [
            'user_id' => $user->id,
            'total_points' => 100
        ]);
    }

    public function test_add_points_with_negative_points_throws_exception(): void
    {
        // Arrange
        $user = User::factory()->create();
        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $service->addPoints($user, -50, 'Negative Points');
    }

    public function test_add_points_triggers_level_up_when_threshold_reached(): void
    {
        // Arrange
        $user = User::factory()->create();
        $gamification = UserGamification::factory()->create([
            'user_id' => $user->id,
            'total_points' => 95,
            'current_level' => 'Rookie'
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendPointsNotification')
            ->once();
        $notificationService->shouldReceive('sendLevelUpNotification')
            ->once()
            ->with($user, 'Rookie', 'Beginner');

        $dispatcher = Mockery::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->atLeast()
            ->twice(); // PointsAwarded + UserLevelUp

        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $service->addPoints($user, 10, 'Level Up Points');

        // Assert
        $gamification->refresh();
        $this->assertEquals('Beginner', $gamification->current_level);
        $this->assertEquals(105, $gamification->total_points);
    }

    public function test_get_global_ranking_returns_users(): void
    {
        // Arrange
        UserGamification::factory()->count(3)->create();
        
        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $ranking = $service->getGlobalRanking(2);

        // Assert
        $this->assertCount(2, $ranking);
        $this->assertInstanceOf(UserGamification::class, $ranking[0]);
    }

    public function test_get_global_ranking_with_empty_database(): void
    {
        // Arrange
        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $ranking = $service->getGlobalRanking(10);

        // Assert
        $this->assertCount(0, $ranking);
        $this->assertIsArray($ranking);
    }

    public function test_update_streak_increments_streak_days(): void
    {
        // Arrange
        $user = User::factory()->create();
        $gamification = UserGamification::factory()->create([
            'user_id' => $user->id,
            'streak_days' => 5,
            'last_activity_date' => now()->subDays(1)
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $service->updateStreak($user);

        // Assert
        $gamification->refresh();
        $this->assertEquals(6, $gamification->streak_days);
    }

    public function test_update_streak_resets_when_break_occurs(): void
    {
        // Arrange
        $user = User::factory()->create();
        $gamification = UserGamification::factory()->create([
            'user_id' => $user->id,
            'streak_days' => 5,
            'last_activity_date' => now()->subDays(3) // Quebrou a sequência
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $service->updateStreak($user);

        // Assert
        $gamification->refresh();
        $this->assertEquals(1, $gamification->streak_days);
    }

    public function test_update_streak_does_nothing_if_already_updated_today(): void
    {
        // Arrange
        $user = User::factory()->create();
        $gamification = UserGamification::factory()->create([
            'user_id' => $user->id,
            'streak_days' => 5,
            'last_activity_date' => now() // Já atualizado hoje
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $service->updateStreak($user);

        // Assert
        $gamification->refresh();
        $this->assertEquals(5, $gamification->streak_days); // Não mudou
    }

    public function test_get_user_stats_returns_correct_data(): void
    {
        // Arrange
        $user = User::factory()->create();
        UserGamification::factory()->create([
            'user_id' => $user->id,
            'total_points' => 250,
            'current_level' => 'Explorer',
            'streak_days' => 3
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $stats = $service->getUserStats($user);

        // Assert
        $this->assertEquals(250, $stats['total_points']);
        $this->assertEquals('Explorer', $stats['current_level']);
        $this->assertEquals(3, $stats['streak_days']);
        $this->assertArrayHasKey('points_to_next_level', $stats);
        $this->assertArrayHasKey('level_progress', $stats);
    }

    public function test_get_user_stats_for_new_user(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $stats = $service->getUserStats($user);

        // Assert
        $this->assertEquals(0, $stats['total_points']);
        $this->assertEquals(1, $stats['current_level']);
        $this->assertEquals(0, $stats['streak_days']);
        $this->assertEquals(100, $stats['points_to_next_level']);
        $this->assertEquals(0, $stats['level_progress']);
    }

    public function test_points_history_is_recorded_when_points_added(): void
    {
        // Arrange
        $user = User::factory()->create();
        UserGamification::factory()->create([
            'user_id' => $user->id,
            'total_points' => 100
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('sendPointsNotification')
            ->once();
        $notificationService->shouldReceive('sendLevelUpNotification')
            ->zeroOrMoreTimes();

        $dispatcher = Mockery::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')
            ->atLeast()
            ->once();

        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $service->addPoints($user, 50, 'Test Reason');

        // Assert
        $this->assertDatabaseHas('points_histories', [
            'user_id' => $user->id,
            'points' => 50,
            'reason' => 'Test Reason',
            'old_total' => 100,
            'new_total' => 150
        ]);
    }

    public function test_calculate_level_progress_correctly(): void
    {
        // Arrange
        $user = User::factory()->create();
        UserGamification::factory()->create([
            'user_id' => $user->id,
            'total_points' => 150 // 50 pontos no nível Beginner (100-500)
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $stats = $service->getUserStats($user);

        // Assert
        $this->assertEquals(13, $stats['level_progress']); // 50/400 * 100 = 12.5% ≈ 13%
    }

    public function test_calculate_points_to_next_level(): void
    {
        // Arrange
        $user = User::factory()->create();
        UserGamification::factory()->create([
            'user_id' => $user->id,
            'total_points' => 150
        ]);

        $notificationService = Mockery::mock(NotificationService::class);
        $dispatcher = Mockery::mock(Dispatcher::class);
        $service = new GamificationService($notificationService, $dispatcher);

        // Act
        $stats = $service->getUserStats($user);

        // Assert
        $this->assertEquals(350, $stats['points_to_next_level']); // 500 - 150 = 350
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 