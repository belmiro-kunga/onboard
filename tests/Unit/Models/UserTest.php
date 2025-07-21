<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Module;
use App\Models\QuizAttempt;
use App\Models\UserGamification;
use App\Models\UserProgress;
use App\Models\Certificate;
use App\Models\Notification;
use App\Models\Achievement;
use App\Models\PointsHistory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_has_fillable_attributes(): void
    {
        $fillable = [
            'name', 'email', 'password', 'role', 'is_active', 'avatar',
            'bio', 'phone', 'birthdate', 'department', 'position',
            'hire_date', 'last_login_at', 'email_verified_at'
        ];

        $this->assertEquals($fillable, $this->user->getFillable());
    }

    /** @test */
    public function it_has_hidden_attributes(): void
    {
        $hidden = [
            'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'
        ];

        $this->assertEquals($hidden, $this->user->getHidden());
    }

    /** @test */
    public function it_casts_attributes_correctly(): void
    {
        $casts = [
            'email_verified_at' => 'datetime',
            'hire_date' => 'date',
            'birthdate' => 'date',
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'preferences' => 'array',
            'password' => 'hashed',
        ];

        foreach ($casts as $attribute => $cast) {
            $this->assertEquals($cast, $this->user->getCasts()[$attribute]);
        }
    }

    /** @test */
    public function it_has_quiz_attempts_relationship(): void
    {
        $quizAttempt = QuizAttempt::factory()->for($this->user)->create();

        $this->assertTrue($this->user->quizAttempts()->exists());
        $this->assertEquals($quizAttempt->id, $this->user->quizAttempts->first()->id);
    }

    /** @test */
    public function it_has_progress_relationship(): void
    {
        $progress = UserProgress::factory()->for($this->user)->create();

        $this->assertTrue($this->user->progress()->exists());
        $this->assertEquals($progress->id, $this->user->progress->first()->id);
    }

    /** @test */
    public function it_has_gamification_relationship(): void
    {
        $gamification = UserGamification::factory()->for($this->user)->create();

        $this->assertInstanceOf(UserGamification::class, $this->user->gamification);
        $this->assertEquals($gamification->id, $this->user->gamification->id);
    }

    /** @test */
    public function it_has_certificates_relationship(): void
    {
        $certificate = Certificate::factory()->for($this->user)->create();

        $this->assertTrue($this->user->certificates()->exists());
        $this->assertEquals($certificate->id, $this->user->certificates->first()->id);
    }

    /** @test */
    public function it_has_notifications_relationship(): void
    {
        $notification = Notification::factory()->for($this->user)->create();

        $this->assertTrue($this->user->notifications()->exists());
        $this->assertEquals($notification->id, $this->user->notifications->first()->id);
    }

    /** @test */
    public function it_has_completed_modules_relationship(): void
    {
        $module = Module::factory()->create();
        UserProgress::factory()
            ->for($this->user)
            ->for($module)
            ->create(['status' => 'completed']);

        $this->assertTrue($this->user->completedModules()->exists());
        $this->assertEquals($module->id, $this->user->completedModules->first()->id);
    }

    /** @test */
    public function it_has_points_history_relationship(): void
    {
        $pointsHistory = PointsHistory::factory()->for($this->user)->create();

        $this->assertTrue($this->user->pointsHistory()->exists());
        $this->assertEquals($pointsHistory->id, $this->user->pointsHistory->first()->id);
    }

    /** @test */
    public function it_has_achievements_relationship(): void
    {
        $achievement = Achievement::factory()->create();
        $this->user->achievements()->attach($achievement, [
            'earned_at' => now(),
            'points_awarded' => 100
        ]);

        $this->assertTrue($this->user->achievements()->exists());
        $this->assertEquals($achievement->id, $this->user->achievements->first()->id);
    }

    /** @test */
    public function it_calculates_progress_percentage_correctly(): void
    {
        // Create 4 active modules
        Module::factory()->count(4)->active()->create();
        
        // Complete 2 modules
        $completedModules = Module::factory()->count(2)->create();
        foreach ($completedModules as $module) {
            UserProgress::factory()
                ->for($this->user)
                ->for($module)
                ->create(['status' => 'completed']);
        }

        $this->assertEquals(50, $this->user->getProgressPercentage());
    }

    /** @test */
    public function it_returns_zero_progress_when_no_modules_exist(): void
    {
        $this->assertEquals(0, $this->user->getProgressPercentage());
    }

    /** @test */
    public function it_gets_current_level_from_gamification(): void
    {
        UserGamification::factory()
            ->for($this->user)
            ->create(['current_level' => 'Avançado']);

        $this->assertEquals('Avançado', $this->user->getCurrentLevel());
    }

    /** @test */
    public function it_returns_default_level_when_no_gamification(): void
    {
        $this->assertEquals('Iniciante', $this->user->getCurrentLevel());
    }

    /** @test */
    public function it_gets_total_points_from_gamification(): void
    {
        UserGamification::factory()
            ->for($this->user)
            ->create(['total_points' => 500]);

        $this->assertEquals(500, $this->user->getTotalPoints());
    }

    /** @test */
    public function it_returns_zero_points_when_no_gamification(): void
    {
        $this->assertEquals(0, $this->user->getTotalPoints());
    }

    /** @test */
    public function it_gets_next_recommended_module_title(): void
    {
        $module1 = Module::factory()->create(['order_index' => 1, 'title' => 'First Module']);
        $module2 = Module::factory()->create(['order_index' => 2, 'title' => 'Second Module']);

        // Complete first module
        UserProgress::factory()
            ->for($this->user)
            ->for($module1)
            ->create(['status' => 'completed']);

        $this->assertEquals('Second Module', $this->user->getNextRecommendedModule());
    }

    /** @test */
    public function it_returns_null_when_all_modules_completed(): void
    {
        $module = Module::factory()->create();
        UserProgress::factory()
            ->for($this->user)
            ->for($module)
            ->create(['status' => 'completed']);

        $this->assertNull($this->user->getNextRecommendedModule());
    }

    /** @test */
    public function it_gets_next_recommended_module_object(): void
    {
        $module1 = Module::factory()->create(['order_index' => 1]);
        $module2 = Module::factory()->create(['order_index' => 2]);

        // Complete first module
        UserProgress::factory()
            ->for($this->user)
            ->for($module1)
            ->create(['status' => 'completed']);

        $nextModule = $this->user->getNextRecommendedModuleObject();
        $this->assertInstanceOf(Module::class, $nextModule);
        $this->assertEquals($module2->id, $nextModule->id);
    }

    /** @test */
    public function it_checks_user_role_correctly(): void
    {
        $adminUser = User::factory()->admin()->create();
        $managerUser = User::factory()->manager()->create();
        $employeeUser = User::factory()->employee()->create();

        $this->assertTrue($adminUser->hasRole('admin'));
        $this->assertFalse($adminUser->hasRole('manager'));

        $this->assertTrue($managerUser->hasRole('manager'));
        $this->assertTrue($managerUser->isManager());

        $this->assertTrue($employeeUser->hasRole('employee'));
        $this->assertFalse($employeeUser->isManager());
    }

    /** @test */
    public function it_checks_if_user_is_active(): void
    {
        $activeUser = User::factory()->active()->create();
        $inactiveUser = User::factory()->inactive()->create();

        $this->assertTrue($activeUser->isActive());
        $this->assertFalse($inactiveUser->isActive());
    }

    /** @test */
    public function it_has_active_scope(): void
    {
        User::factory()->active()->count(3)->create();
        User::factory()->inactive()->count(2)->create();

        $activeUsers = User::active()->get();
        $this->assertCount(4, $activeUsers); // 3 + 1 from setUp
    }

    /** @test */
    public function it_has_by_role_scope(): void
    {
        User::factory()->admin()->count(2)->create();
        User::factory()->manager()->count(3)->create();

        $adminUsers = User::byRole('admin')->get();
        $managerUsers = User::byRole('manager')->get();

        $this->assertCount(2, $adminUsers);
        $this->assertCount(3, $managerUsers);
    }

    /** @test */
    public function it_formats_name_correctly(): void
    {
        $user = User::factory()->create(['name' => 'john doe']);
        $this->assertEquals('John doe', $user->formatted_name);
    }

    /** @test */
    public function it_generates_avatar_url_correctly(): void
    {
        // User with custom avatar
        $userWithAvatar = User::factory()->create(['avatar' => 'custom-avatar.jpg']);
        $this->assertStringContains('storage/avatars/custom-avatar.jpg', $userWithAvatar->avatar_url);

        // User without avatar
        $userWithoutAvatar = User::factory()->create(['avatar' => null, 'name' => 'John Doe']);
        $this->assertStringContains('ui-avatars.com', $userWithoutAvatar->avatar_url);
        $this->assertStringContains('John+Doe', $userWithoutAvatar->avatar_url);
    }

    /** @test */
    public function it_gets_full_name_attribute(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $this->assertEquals('John Doe', $user->full_name);
    }

    /** @test */
    public function it_gets_initials_correctly(): void
    {
        $user = User::factory()->create(['name' => 'John Doe Smith']);
        $this->assertEquals('JD', $user->initials);

        $singleNameUser = User::factory()->create(['name' => 'John']);
        $this->assertEquals('J', $singleNameUser->initials);
    }

    /** @test */
    public function it_has_unread_notifications_relationship(): void
    {
        Notification::factory()->for($this->user)->create(['read_at' => null]);
        Notification::factory()->for($this->user)->create(['read_at' => now()]);

        $this->assertCount(1, $this->user->unreadNotifications);
    }

    /** @test */
    public function it_counts_unread_notifications_correctly(): void
    {
        Notification::factory()->for($this->user)->count(3)->create(['read_at' => null]);
        Notification::factory()->for($this->user)->count(2)->create(['read_at' => now()]);

        $this->assertEquals(3, $this->user->unreadNotificationsCount());
    }

    /** @test */
    public function it_can_add_points_through_service(): void
    {
        // Mock the gamification service
        $this->mock(\App\Services\GamificationService::class, function ($mock) {
            $mock->shouldReceive('addPoints')
                ->once()
                ->with($this->user, 100, 'test reason')
                ->andReturn(['success' => true, 'points_added' => 100]);
        });

        $result = $this->user->addPoints(100, 'test reason');
        $this->assertTrue($result['success']);
        $this->assertEquals(100, $result['points_added']);
    }

    /** @test */
    public function it_can_check_achievements_through_service(): void
    {
        // Mock the gamification service
        $this->mock(\App\Services\GamificationService::class, function ($mock) {
            $mock->shouldReceive('checkAchievements')
                ->once()
                ->with($this->user)
                ->andReturn(['new_achievements' => []]);
        });

        $result = $this->user->checkAchievements();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('new_achievements', $result);
    }

    /** @test */
    public function it_can_send_notification_through_service(): void
    {
        // Mock the notification service
        $this->mock(\App\Services\NotificationService::class, function ($mock) {
            $mock->shouldReceive('sendToUser')
                ->once()
                ->with(
                    $this->user,
                    'Test Title',
                    'Test Message',
                    'info',
                    null,
                    null,
                    null,
                    []
                )
                ->andReturn(true);
        });

        $result = $this->user->sendNotification('Test Title', 'Test Message');
        $this->assertTrue($result);
    }
}