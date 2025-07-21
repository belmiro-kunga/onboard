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

class UserLevelUp implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User Usuário que subiu de nível
     */
    public User $user;

    /**
     * @var string Nível anterior do usuário
     */
    public string $oldLevel;

    /**
     * @var string Novo nível do usuário
     */
    public string $newLevel;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $oldLevel, string $newLevel)
    {
        $this->user = $user;
        $this->oldLevel = $oldLevel;
        $this->newLevel = $newLevel;
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
            'type' => 'level_up',
            'user_id' => $this->user->id,
            'old_level' => $this->oldLevel,
            'new_level' => $this->newLevel,
            'message' => "Parabéns! Você subiu para o nível {$this->newLevel}!",
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.level.up';
    }
}