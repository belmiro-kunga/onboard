<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\UserGamification;
use App\Models\PointsHistory;
use App\Services\PointsService;
use App\Contracts\EventDispatcherInterface;
use App\Events\PointsAwarded;
use App\Events\PointsRemoved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointsServiceTest extends TestCase
{
    use RefreshDatabase;

    private PointsService $pointsService;
    private EventDispatcherInterface $eventDispatcher;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->pointsService = new PointsService($this->eventDispatcher);
    }

    /**
     * Teste de adição de pontos válidos
     */
    public function test_add_points_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $points = 100;
        $reason = 'Test points';

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PointsAwarded::class));

        // Act
        $result = $this->pointsService->addPoints($user, $points, $reason);

        // Assert
        $this->assertTrue($result);
        
        $user->refresh();
        $this->assertEquals(100, $user->gamification->total_points);
        
        $this->assertDatabaseHas('points_histories', [
            'user_id' => $user->id,
            'points' => 100,
            'reason' => 'Test points',
            'old_total' => 0,
            'new_total' => 100
        ]);
    }

    /**
     * Teste de adição de pontos negativos
     */
    public function test_add_negative_points_fails(): void
    {
        // Arrange
        $user = User::factory()->create();
        $points = -50;

        $this->eventDispatcher->expects($this->never())
            ->method('dispatch');

        // Act
        $result = $this->pointsService->addPoints($user, $points, 'Negative test');

        // Assert
        $this->assertFalse($result);
        
        $user->refresh();
        $this->assertEquals(0, $user->gamification->total_points);
    }

    /**
     * Teste de adição de zero pontos
     */
    public function test_add_zero_points_fails(): void
    {
        // Arrange
        $user = User::factory()->create();
        $points = 0;

        $this->eventDispatcher->expects($this->never())
            ->method('dispatch');

        // Act
        $result = $this->pointsService->addPoints($user, $points, 'Zero test');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Teste de remoção de pontos válidos
     */
    public function test_remove_points_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 200,
            'current_level' => 'Beginner',
            'streak_days' => 0
        ]);

        $points = 50;
        $reason = 'Test removal';

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PointsRemoved::class));

        // Act
        $result = $this->pointsService->removePoints($user, $points, $reason);

        // Assert
        $this->assertTrue($result);
        
        $user->refresh();
        $this->assertEquals(150, $user->gamification->total_points);
        
        $this->assertDatabaseHas('points_histories', [
            'user_id' => $user->id,
            'points' => -50,
            'reason' => 'Test removal',
            'old_total' => 200,
            'new_total' => 150
        ]);
    }

    /**
     * Teste de remoção de pontos que não pode ficar negativo
     */
    public function test_remove_points_does_not_go_negative(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 50,
            'current_level' => 'Rookie',
            'streak_days' => 0
        ]);

        $points = 100;

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PointsRemoved::class));

        // Act
        $result = $this->pointsService->removePoints($user, $points, 'Over removal');

        // Assert
        $this->assertTrue($result);
        
        $user->refresh();
        $this->assertEquals(0, $user->gamification->total_points);
        
        $this->assertDatabaseHas('points_histories', [
            'user_id' => $user->id,
            'points' => -100,
            'old_total' => 50,
            'new_total' => 0
        ]);
    }

    /**
     * Teste de obtenção de pontos totais
     */
    public function test_get_total_points(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 500,
            'current_level' => 'Explorer',
            'streak_days' => 0
        ]);

        // Act
        $totalPoints = $this->pointsService->getTotalPoints($user);

        // Assert
        $this->assertEquals(500, $totalPoints);
    }

    /**
     * Teste de obtenção de pontos totais para usuário sem gamification
     */
    public function test_get_total_points_for_user_without_gamification(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $totalPoints = $this->pointsService->getTotalPoints($user);

        // Assert
        $this->assertEquals(0, $totalPoints);
        
        // Verificar que gamification foi criado
        $this->assertDatabaseHas('user_gamifications', [
            'user_id' => $user->id,
            'total_points' => 0
        ]);
    }

    /**
     * Teste de obtenção de histórico de pontos
     */
    public function test_get_points_history(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 300,
            'current_level' => 'Beginner',
            'streak_days' => 0
        ]);

        // Criar histórico de pontos
        PointsHistory::create([
            'user_id' => $user->id,
            'points' => 100,
            'reason' => 'First points',
            'old_total' => 0,
            'new_total' => 100,
            'awarded_at' => now()->subDays(2)
        ]);

        PointsHistory::create([
            'user_id' => $user->id,
            'points' => 200,
            'reason' => 'Second points',
            'old_total' => 100,
            'new_total' => 300,
            'awarded_at' => now()->subDay(1)
        ]);

        // Act
        $history = $this->pointsService->getPointsHistory($user, 5);

        // Assert
        $this->assertCount(2, $history);
        $this->assertEquals('Second points', $history[0]['reason']);
        $this->assertEquals('First points', $history[1]['reason']);
    }

    /**
     * Teste de múltiplas adições de pontos
     */
    public function test_multiple_points_additions(): void
    {
        // Arrange
        $user = User::factory()->create();
        $points = [50, 75, 100, 25];

        $this->eventDispatcher->expects($this->exactly(4))
            ->method('dispatch')
            ->with($this->isInstanceOf(PointsAwarded::class));

        // Act
        foreach ($points as $point) {
            $result = $this->pointsService->addPoints($user, $point, "Test {$point}");
            $this->assertTrue($result);
        }

        // Assert
        $user->refresh();
        $this->assertEquals(250, $user->gamification->total_points);
        
        $historyCount = PointsHistory::where('user_id', $user->id)->count();
        $this->assertEquals(4, $historyCount);
    }

    /**
     * Teste de transação em caso de erro
     */
    public function test_transaction_rollback_on_error(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 100,
            'current_level' => 'Beginner',
            'streak_days' => 0
        ]);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->willThrowException(new \Exception('Event dispatch failed'));

        // Act
        $result = $this->pointsService->addPoints($user, 50, 'Error test');

        // Assert
        $this->assertFalse($result);
        
        $user->refresh();
        $this->assertEquals(100, $user->gamification->total_points);
        
        // Verificar que não foi criado histórico
        $this->assertDatabaseMissing('points_histories', [
            'user_id' => $user->id,
            'points' => 50
        ]);
    }
} 