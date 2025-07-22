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

class OccupationalQualifierCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User Usuário que completou o qualificador
     */
    public User $user;

    /**
     * @var int ID do qualificador
     */
    public int $qualifierId;

    /**
     * @var string Título do qualificador
     */
    public string $qualifierTitle;

    /**
     * @var int Pontuação obtida
     */
    public int $score;

    /**
     * @var string Categoria do qualificador
     */
    public string $category;

    /**
     * @var string|null Nível do qualificador
     */
    public ?string $level;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, int $qualifierId, string $qualifierTitle, int $score, string $category, ?string $level = null)
    {
        $this->user = $user;
        $this->qualifierId = $qualifierId;
        $this->qualifierTitle = $qualifierTitle;
        $this->score = $score;
        $this->category = $category;
        $this->level = $level;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'qualifier.completed';
    }

    public function broadcastWith(): array
    {
        $performance = $this->getPerformanceLevel();
        
        return [
            'qualifier' => [
                'id' => $this->qualifierId,
                'title' => $this->qualifierTitle,
                'category' => $this->category,
                'level' => $this->level,
            ],
            'result' => [
                'score' => $this->score,
                'performance' => $performance,
            ],
            'message' => "Qualificador '{$this->qualifierTitle}' concluído com {$this->score}% - {$performance}!"
        ];
    }

    private function getPerformanceLevel(): string
    {
        if ($this->score >= 90) return 'Excelente';
        if ($this->score >= 80) return 'Bom';
        if ($this->score >= 70) return 'Satisfatório';
        if ($this->score >= 60) return 'Regular';
        return 'Insuficiente';
    }
}