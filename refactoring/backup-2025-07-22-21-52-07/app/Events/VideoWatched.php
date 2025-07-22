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

class VideoWatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User Usuário que assistiu o vídeo
     */
    public User $user;

    /**
     * @var int ID do módulo
     */
    public int $moduleId;

    /**
     * @var string ID do vídeo
     */
    public string $videoId;

    /**
     * @var string Título do vídeo
     */
    public string $videoTitle;

    /**
     * @var int Tempo assistido em segundos
     */
    public int $watchTime;

    /**
     * @var int Duração total do vídeo em segundos
     */
    public int $totalDuration;

    /**
     * @var float Percentual de conclusão do vídeo
     */
    public float $completionPercentage;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, int $moduleId, string $videoId, string $videoTitle, int $watchTime, int $totalDuration)
    {
        $this->user = $user;
        $this->moduleId = $moduleId;
        $this->videoId = $videoId;
        $this->videoTitle = $videoTitle;
        $this->watchTime = $watchTime;
        $this->totalDuration = $totalDuration;
        $this->completionPercentage = ($watchTime / $totalDuration) * 100;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'video.watched';
    }

    public function broadcastWith(): array
    {
        return [
            'video' => [
                'id' => $this->videoId,
                'title' => $this->videoTitle,
                'module_id' => $this->moduleId,
            ],
            'progress' => [
                'watch_time' => $this->watchTime,
                'total_duration' => $this->totalDuration,
                'completion_percentage' => round($this->completionPercentage, 1),
            ],
            'message' => $this->completionPercentage >= 80 
                ? "Vídeo '{$this->videoTitle}' assistido completamente!"
                : "Progresso no vídeo '{$this->videoTitle}': {$this->completionPercentage}%"
        ];
    }
}