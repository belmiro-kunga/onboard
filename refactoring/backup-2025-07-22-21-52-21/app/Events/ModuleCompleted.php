<?php

namespace App\Events;

use App\Models\User;
use App\Models\Module;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModuleCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User Usuário que completou o módulo
     */
    public User $user;

    /**
     * @var Module Módulo que foi completado
     */
    public Module $module;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Module $module)
    {
        $this->user = $user;
        $this->module = $module;
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
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'module.completed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'module' => [
                'id' => $this->module->id,
                'title' => $this->module->title,
                'category' => $this->module->category,
                'difficulty_level' => $this->module->difficulty_level,
            ],
            'message' => "Parabéns! Você concluiu o módulo '{$this->module->title}'!"
        ];
    }
}