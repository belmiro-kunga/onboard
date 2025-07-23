<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para progresso de visualização de vídeos
 * 
 * @property int $id
 * @property int $user_id
 * @property int $lesson_video_id
 * @property int $watch_time_seconds
 * @property int $current_time_seconds
 * @property float $playback_speed
 * @property \Carbon\Carbon $last_watched_at
 * @property array|null $metadata
 */
class VideoViewProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_video_id',
        'watch_time_seconds',
        'current_time_seconds',
        'playback_speed',
        'last_watched_at',
        'metadata',
    ];

    protected $casts = [
        'watch_time_seconds' => 'integer',
        'current_time_seconds' => 'integer',
        'playback_speed' => 'float',
        'last_watched_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com vídeo da aula
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(LessonVideo::class, 'lesson_video_id');
    }

    /**
     * Obter porcentagem assistida
     */
    public function getWatchPercentageAttribute(): float
    {
        if (!$this->video->duration_seconds) {
            return 0;
        }

        return min(100, round(($this->watch_time_seconds / $this->video->duration_seconds) * 100, 2));
    }

    /**
     * Obter posição atual formatada
     */
    public function getFormattedCurrentTimeAttribute(): string
    {
        $minutes = floor($this->current_time_seconds / 60);
        $seconds = $this->current_time_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Verificar se foi assistido completamente
     */
    public function isFullyWatched(): bool
    {
        return $this->watch_percentage >= 90; // Considera 90% como completo
    }

    /**
     * Scopes
     */
    public function scopeFullyWatched($query)
    {
        return $query->whereRaw('watch_time_seconds >= (SELECT duration_seconds * 0.9 FROM lesson_videos WHERE id = video_view_progress.lesson_video_id)');
    }

    public function scopeRecentlyWatched($query)
    {
        return $query->where('last_watched_at', '>=', now()->subHours(24));
    }
}