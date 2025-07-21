<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\UserGamification;
use App\Services\LevelService;
use App\Contracts\EventDispatcherInterface;
use App\Contracts\NotificationServiceInterface;
use App\Events\UserLevelUp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LevelServiceTest extends TestCase
{
    use RefreshDatabase;

    private LevelService $levelService;
    private EventDispatcherInterface $eventDispatcher;
    private NotificationServiceInterface $notificationService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->notificationService = $this->createMock(NotificationServiceInterface::class);
        $this->levelService = new LevelService($this->eventDispatcher, $this->notificationService);
    }

    /**
     * Teste de cálculo de nível para diferentes pontuações
     */
    public function test_calculate_level_for_different_points(): void
    {
        $testCases = [
            [0, 'Rookie'],
            [50, 'Rookie'],
            [99, 'Rookie'],
            [100, 'Beginner'],
            [250, 'Beginner'],
            [499, 'Beginner'],
            [500, 'Explorer'],
            [750, 'Explorer'],
            [999, 'Explorer'],
            [1000, 'Intermediate'],
            [1500, 'Intermediate'],
            [1999, 'Intermediate'],
            [2000, 'Advanced'],
            [3500, 'Advanced'],
            [4999, 'Advanced'],
            [5000, 'Expert'],
            [7500, 'Expert'],
            [9999, 'Expert'],
            [10000, 'Master'],
            [15000, 'Master']
        ];

        foreach ($testCases as [$points, $expectedLevel]) {
            $level = $this->levelService->calculateLevel($points);
            $this->assertEquals($expectedLevel, $level, "Points: {$points} should be level {$expectedLevel}");
        }
    }

    /**
     * Teste de verificação de level up
     */
    public function test_check_level_up(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 50,
            'current_level' => 'Rookie',
            'streak_days' => 0
        ]);

        // Act & Assert
        $this->assertTrue($this->levelService->checkLevelUp($user, 150)); // Deve subir para Beginner
        $this->assertFalse($this->levelService->checkLevelUp($user, 80)); // Não deve subir
        $this->assertTrue($this->levelService->checkLevelUp($user, 600)); // Deve subir para Explorer
    }

    /**
     * Teste de atualização de nível do usuário
     */
    public function test_update_user_level(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 50,
            'current_level' => 'Rookie',
            'streak_days' => 0
        ]);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(UserLevelUp::class));

        $this->notificationService->expects($this->once())
            ->method('sendLevelUpNotification')
            ->with($user, 'Rookie', 'Beginner');

        // Act
        $result = $this->levelService->updateUserLevel($user, 150);

        // Assert
        $this->assertTrue($result);
        
        $user->refresh();
        $this->assertEquals('Beginner', $user->gamification->current_level);
    }

    /**
     * Teste de atualização de nível sem mudança
     */
    public function test_update_user_level_no_change(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 50,
            'current_level' => 'Rookie',
            'streak_days' => 0
        ]);

        $this->eventDispatcher->expects($this->never())
            ->method('dispatch');

        $this->notificationService->expects($this->never())
            ->method('sendLevelUpNotification');

        // Act
        $result = $this->levelService->updateUserLevel($user, 80);

        // Assert
        $this->assertFalse($result);
        
        $user->refresh();
        $this->assertEquals('Rookie', $user->gamification->current_level);
    }

    /**
     * Teste de obtenção do nível atual do usuário
     */
    public function test_get_user_level(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 500,
            'current_level' => 'Explorer',
            'streak_days' => 0
        ]);

        // Act
        $level = $this->levelService->getUserLevel($user);

        // Assert
        $this->assertEquals('Explorer', $level);
    }

    /**
     * Teste de obtenção do nível para usuário sem gamification
     */
    public function test_get_user_level_without_gamification(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $level = $this->levelService->getUserLevel($user);

        // Assert
        $this->assertEquals('Rookie', $level);
    }

    /**
     * Teste de cálculo de pontos para próximo nível
     */
    public function test_calculate_points_to_next_level(): void
    {
        $testCases = [
            [0, 100],    // Rookie precisa de 100 para Beginner
            [50, 50],    // Rookie com 50 precisa de 50 para Beginner
            [100, 400],  // Beginner precisa de 400 para Explorer
            [250, 250],  // Beginner com 250 precisa de 250 para Explorer
            [500, 500],  // Explorer precisa de 500 para Intermediate
            [750, 250],  // Explorer com 750 precisa de 250 para Intermediate
            [10000, 0]   // Master não tem próximo nível
        ];

        foreach ($testCases as [$currentPoints, $expectedPoints]) {
            $pointsNeeded = $this->levelService->calculatePointsToNextLevel($currentPoints);
            $this->assertEquals($expectedPoints, $pointsNeeded, "Current points: {$currentPoints}");
        }
    }

    /**
     * Teste de cálculo de progresso do nível
     */
    public function test_calculate_level_progress(): void
    {
        $testCases = [
            [0, 0],      // Rookie no início
            [50, 50],    // Rookie no meio
            [99, 99],    // Rookie quase no fim
            [100, 0],    // Beginner no início
            [250, 37],   // Beginner no meio (150/400 * 100)
            [499, 99],   // Beginner quase no fim
            [500, 0],    // Explorer no início
            [750, 50],   // Explorer no meio (250/500 * 100)
            [10000, 100] // Master no máximo
        ];

        foreach ($testCases as [$currentPoints, $expectedProgress]) {
            $progress = $this->levelService->calculateLevelProgress($currentPoints);
            $this->assertEquals($expectedProgress, $progress, "Current points: {$currentPoints}");
        }
    }

    /**
     * Teste de obtenção de níveis disponíveis
     */
    public function test_get_available_levels(): void
    {
        // Act
        $levels = $this->levelService->getAvailableLevels();

        // Assert
        $expectedLevels = [
            'Rookie' => 0,
            'Beginner' => 100,
            'Explorer' => 500,
            'Intermediate' => 1000,
            'Advanced' => 2000,
            'Expert' => 5000,
            'Master' => 10000
        ];

        $this->assertEquals($expectedLevels, $levels);
    }

    /**
     * Teste de múltiplos level ups
     */
    public function test_multiple_level_ups(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 0,
            'current_level' => 'Rookie',
            'streak_days' => 0
        ]);

        $this->eventDispatcher->expects($this->exactly(3))
            ->method('dispatch')
            ->with($this->isInstanceOf(UserLevelUp::class));

        $this->notificationService->expects($this->exactly(3))
            ->method('sendLevelUpNotification');

        // Act - Simular múltiplos level ups
        $this->levelService->updateUserLevel($user, 150);  // Rookie -> Beginner
        $this->levelService->updateUserLevel($user, 600);  // Beginner -> Explorer
        $this->levelService->updateUserLevel($user, 1100); // Explorer -> Intermediate

        // Assert
        $user->refresh();
        $this->assertEquals('Intermediate', $user->gamification->current_level);
    }

    /**
     * Teste de tratamento de erro na atualização de nível
     */
    public function test_error_handling_in_level_update(): void
    {
        // Arrange
        $user = User::factory()->create();
        // Não criar gamification para simular erro

        // Act
        $result = $this->levelService->updateUserLevel($user, 150);

        // Assert
        $this->assertFalse($result);
    }
} 