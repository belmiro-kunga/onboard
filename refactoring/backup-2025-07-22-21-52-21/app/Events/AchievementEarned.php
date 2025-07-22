<?php

namespace App\Events;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementEarned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User Usuário que conquistou a achievement
     */
    public User $user;

    /**
     * @var Achievement Achievement conquistada
     */
    public Achievement $achievement;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Achievement $achievement)
    {
        $this->user = $user;
        $this->achievement = $achievement;
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
            'type' => 'achievement_earned',
            'user_id' => $this->user->id,
            'achievement' => [
                'id' => $this->achievement->id,
                'name' => $this->achievement->name,
                'description' => $this->achievement->description,
                'icon_url' => $this->achievement->icon_url,
                'rarity' => $this->achievement->rarity,
                'rarity_color' => $this->achievement->rarity_color,
                'points_reward' => $this->achievement->points_reward,
                'unlock_message' => $this->achievement->unlock_message
            ],
            'message' => $this->achievement->unlock_message ?: "Nova conquista desbloqueada: {$this->achievement->name}!",
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'achievement.earned';
    }
}