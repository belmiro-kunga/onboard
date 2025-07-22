<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PointsAwarded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User Usuário que recebeu os pontos
     */
    public User $user;

    /**
     * @var int Quantidade de pontos concedidos
     */
    public int $points;

    /**
     * @var string Motivo da concessão dos pontos
     */
    public string $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, int $points, string $reason)
    {
        $this->user = $user;
        $this->points = $points;
        $this->reason = $reason;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'type' => 'points_awarded',
            'user_id' => $this->user->id,
            'points' => $this->points,
            'reason' => $this->reason,
            'message' => "+{$this->points} pontos - {$this->reason}",
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'points.awarded';
    }
}