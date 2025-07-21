<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Services\ActivityTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTrackingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_track_activity_and_get_stats(): void
    {
        $user = User::factory()->create();
        $service = new ActivityTrackingService();
        $service->trackActivity($user, 'login', ['ip' => '127.0.0.1']);
        $stats = $service->getUserActivityStats($user);
        $this->assertGreaterThanOrEqual(1, $stats['total_activities']);
        $this->assertEquals('login', $stats['most_common_action']);
    }
} 