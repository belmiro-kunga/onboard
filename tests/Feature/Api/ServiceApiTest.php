<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Certificate;
use App\Models\UserGamification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_gamification_api_endpoints(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        UserGamification::factory()->create([
            'user_id' => $user->id,
            'total_points' => 500,
            'current_level' => 'Explorer'
        ]);

        // Act & Assert - Get user stats
        $response = $this->getJson('/api/gamification/stats');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_points',
                'current_level',
                'streak_days',
                'points_to_next_level',
                'level_progress'
            ]);

        // Act & Assert - Get global ranking
        $response = $this->getJson('/api/gamification/ranking');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'user_id',
                    'total_points',
                    'current_level',
                    'user' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ]);
    }

    public function test_certificate_api_endpoints(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $certificate = Certificate::factory()->create([
            'user_id' => $user->id,
            'verification_code' => 'TEST123'
        ]);

        // Act & Assert - Get user certificates
        $response = $this->getJson('/api/certificates');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'type',
                    'title',
                    'description',
                    'issued_at',
                    'verification_code'
                ]
            ]);

        // Act & Assert - Verify certificate
        $response = $this->getJson("/api/certificates/verify/{$certificate->verification_code}");
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'type',
                'title',
                'user' => [
                    'id',
                    'name'
                ]
            ]);
    }

    public function test_notification_api_endpoints(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some notifications
        \App\Models\Notification::factory()->count(5)->create([
            'user_id' => $user->id
        ]);

        // Act & Assert - Get user notifications
        $response = $this->getJson('/api/notifications');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'message',
                        'type',
                        'read_at',
                        'created_at'
                    ]
                ],
                'meta' => [
                    'total',
                    'unread_count'
                ]
            ]);

        // Act & Assert - Mark notification as read
        $notification = $user->notifications()->first();
        $response = $this->patchJson("/api/notifications/{$notification->id}/read");
        $response->assertStatus(200);

        // Act & Assert - Mark all as read
        $response = $this->patchJson('/api/notifications/mark-all-read');
        $response->assertStatus(200);
    }

    public function test_module_progress_api_endpoints(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $module = Module::factory()->create([
            'title' => 'Test Module',
            'category' => 'hr'
        ]);

        // Act & Assert - Start module
        $response = $this->postJson("/api/modules/{$module->id}/start");
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'progress' => [
                    'id',
                    'user_id',
                    'module_id',
                    'status',
                    'progress_percentage'
                ]
            ]);

        // Act & Assert - Update progress
        $response = $this->patchJson("/api/modules/{$module->id}/progress", [
            'progress_percentage' => 50
        ]);
        $response->assertStatus(200);

        // Act & Assert - Complete module
        $response = $this->postJson("/api/modules/{$module->id}/complete");
        $response->assertStatus(200);
    }

    public function test_quiz_api_endpoints(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $module = Module::factory()->create();
        $quiz = Quiz::factory()->create([
            'module_id' => $module->id,
            'title' => 'Test Quiz'
        ]);

        // Act & Assert - Start quiz
        $response = $this->postJson("/api/quizzes/{$quiz->id}/start");
        $response->assertStatus(200)
            ->assertJsonStructure([
                'attempt_id',
                'quiz' => [
                    'id',
                    'title',
                    'questions'
                ]
            ]);

        // Act & Assert - Submit quiz answers
        $response = $this->postJson("/api/quizzes/{$quiz->id}/submit", [
            'answers' => [
                1 => 'A',
                2 => 'B',
                3 => 'C'
            ]
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'score',
                'passed',
                'certificate' => [
                    'id',
                    'verification_code'
                ]
            ]);
    }

    public function test_activity_tracking_api_endpoints(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act & Assert - Track activity
        $response = $this->postJson('/api/activities/track', [
            'action' => 'page_view',
            'data' => [
                'page' => '/dashboard',
                'duration' => 120
            ]
        ]);
        $response->assertStatus(200);

        // Act & Assert - Get activity stats
        $response = $this->getJson('/api/activities/stats');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_activities',
                'last_activity',
                'most_common_action',
                'activity_by_day'
            ]);
    }

    public function test_api_error_handling(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act & Assert - Invalid module ID
        $response = $this->getJson('/api/modules/999999');
        $response->assertStatus(404);

        // Act & Assert - Invalid certificate verification
        $response = $this->getJson('/api/certificates/verify/INVALID');
        $response->assertStatus(404);

        // Act & Assert - Unauthorized access
        $this->actingAs(User::factory()->create(['role' => 'employee']));
        $response = $this->getJson('/api/admin/users');
        $response->assertStatus(403);
    }

    public function test_api_rate_limiting(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act - Make multiple rapid requests
        for ($i = 0; $i < 60; $i++) {
            $response = $this->getJson('/api/gamification/stats');
            if ($response->status() === 429) {
                break;
            }
        }

        // Assert - Should eventually hit rate limit
        $this->assertTrue($i < 60, 'Rate limiting not working');
    }

    public function test_api_authentication(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act & Assert - Unauthenticated request
        $response = $this->getJson('/api/gamification/stats');
        $response->assertStatus(401);

        // Act & Assert - Authenticated request
        $this->actingAs($user);
        $response = $this->getJson('/api/gamification/stats');
        $response->assertStatus(200);
    }

    public function test_api_validation(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $module = Module::factory()->create();

        // Act & Assert - Invalid progress percentage
        $response = $this->patchJson("/api/modules/{$module->id}/progress", [
            'progress_percentage' => 150 // Invalid: > 100
        ]);
        $response->assertStatus(422);

        // Act & Assert - Missing required fields
        $response = $this->postJson('/api/activities/track', []);
        $response->assertStatus(422);
    }
} 