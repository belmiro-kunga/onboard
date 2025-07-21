<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Services\CalendarService;
use App\Services\NotificationService;
use App\Services\ActivityTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CalendarServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_automatic_events_creates_events(): void
    {
        $user = User::factory()->create();
        $notificationService = Mockery::mock(NotificationService::class);
        $activityTrackingService = Mockery::mock(ActivityTrackingService::class);
        $activityTrackingService->shouldReceive('trackActivity')->atLeast()->once();
        $service = new CalendarService($notificationService, $activityTrackingService);
        $events = $service->createAutomaticEvents($user);
        $this->assertIsArray($events);
    }
} 