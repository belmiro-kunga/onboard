<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_to_user_creates_notification_and_dispatches_event(): void
    {
        $user = User::factory()->create();
        $dispatcher = Mockery::mock(Dispatcher::class);
        $dispatcher->shouldReceive('dispatch')->once();
        $mailer = Mockery::mock(Mailer::class);

        $service = new NotificationService($dispatcher, $mailer);
        $notification = $service->sendToUser(
            $user,
            'Test Title',
            'Test Message',
            'info',
            null,
            null,
            null,
            [],
            false
        );

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals($user->id, $notification->user_id);
        $this->assertEquals('Test Title', $notification->title);
    }
} 