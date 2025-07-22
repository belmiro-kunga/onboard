<?php

namespace App\Events;

use App\Models\User;
use App\Models\Simulado;
use App\Models\SimuladoTentativa;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SimuladoCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $simulado;
    public $tentativa;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Simulado $simulado, SimuladoTentativa $tentativa)
    {
        $this->user = $user;
        $this->simulado = $simulado;
        $this->tentativa = $tentativa;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
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
            'user_id' => $this->user->id,
            'simulado_id' => $this->simulado->id,
            'simulado_titulo' => $this->simulado->titulo,
            'score' => $this->tentativa->score,
            'passed' => $this->tentativa->isPassed(),
            'tempo_gasto' => $this->tentativa->tempo_formatado,
            'pontos_ganhos' => $this->tentativa->isPassed() ? $this->simulado->pontos_recompensa : 0,
        ];
    }
}
