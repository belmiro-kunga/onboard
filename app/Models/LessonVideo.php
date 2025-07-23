<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Model para representar o vídeo de uma aula
 * 
 * @property int $id
 * @property int $lesson_id
 * @property string $type (youtube|local|vimeo)
 * @property string|null $video_url
 * @property string|null $video_id
 * @property string|null $file_path
 * @property string|null $thumbnail_url
 * @property int|null $duration_seconds
 * @property int|null $file_size
 * @property string|null $quality
 * @property array|null $subtitles
 * @property array|null $chapters
 * @property bool $auto_play_next
 * @property bool $picture_in_picture
 * @property array|null $metadata
 */
class LessonVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'type',
        'video_url',
        'video_id',
        'file_path',
        'thumbnail_url',
        'duration_seconds',
        'file_size',
        'quality',
        'subtitles',
        'chapters',
        'auto_play_next',
        'picture_in_picture',
        'metadata',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'file_size' => 'integer',
        'subtitles' => 'array',
        'chapters' => 'array',
        'auto_play_next' => 'boolean',
        'picture_in_picture' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento com aula
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Relacionamento com progresso de visualização
     */
    public function viewProgress(): HasMany
    {
        return $this->hasMany(VideoViewProgress::class);
    }

    /**
     * Obter URL do vídeo
     */
    public function getVideoUrl(): string
    {
        return match($this->type) {
            'youtube' => "https://www.youtube.com/watch?v={$this->video_id}",
            'vimeo' => "https://vimeo.com/{$this->video_id}",
            'local' => $this->getSecureLocalUrl(),
            default => $this->video_url ?? '',
        };
    }

    /**
     * Obter URL segura para vídeos locais
     */
    public function getSecureLocalUrl(): string
    {
        if (!$this->file_path) {
            return '';
        }

        // Gerar URL temporária assinada (válida por 1 hora)
        return Storage::temporaryUrl(
            'videos/' . $this->file_path,
            now()->addHour()
        );
    }

    /**
     * Obter URL da thumbnail
     */
    public function getThumbnailUrl(): string
    {
        if ($this->thumbnail_url) {
            return $this->thumbnail_url;
        }

        return match($this->type) {
            'youtube' => "https://img.youtube.com/vi/{$this->video_id}/maxresdefault.jpg",
            'vimeo' => $this->getVimeoThumbnail(),
            'local' => $this->getLocalThumbnail(),
            default => asset('images/video-placeholder.jpg'),
        };
    }

    /**
     * Obter thumbnail do Vimeo
     */
    private function getVimeoThumbnail(): string
    {
        // Implementar chamada à API do Vimeo se necessário
        return asset('images/video-placeholder.jpg');
    }

    /**
     * Obter thumbnail de vídeo local
     */
    private function getLocalThumbnail(): string
    {
        if (!$this->file_path) {
            return asset('images/video-placeholder.jpg');
        }

        $thumbnailPath = 'thumbnails/' . pathinfo($this->file_path, PATHINFO_FILENAME) . '.jpg';
        
        if (Storage::exists($thumbnailPath)) {
            return Storage::url($thumbnailPath);
        }

        return asset('images/video-placeholder.jpg');
    }

    /**
     * Obter embed URL para players
     */
    public function getEmbedUrl(): string
    {
        return match($this->type) {
            'youtube' => "https://www.youtube.com/embed/{$this->video_id}?enablejsapi=1&origin=" . url('/'),
            'vimeo' => "https://player.vimeo.com/video/{$this->video_id}",
            'local' => $this->getSecureLocalUrl(),
            default => '',
        };
    }

    /**
     * Verificar se é vídeo do YouTube
     */
    public function isYoutube(): bool
    {
        return $this->type === 'youtube';
    }

    /**
     * Verificar se é vídeo local
     */
    public function isLocal(): bool
    {
        return $this->type === 'local';
    }

    /**
     * Verificar se é vídeo do Vimeo
     */
    public function isVimeo(): bool
    {
        return $this->type === 'vimeo';
    }

    /**
     * Obter duração formatada
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) {
            return 'N/A';
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Obter tamanho do arquivo formatado
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Adicionar legenda
     */
    public function addSubtitle(string $language, string $filePath, string $label = null): void
    {
        $subtitles = $this->subtitles ?? [];
        $subtitles[$language] = [
            'file_path' => $filePath,
            'label' => $label ?? $language,
            'created_at' => now()->toISOString(),
        ];

        $this->update(['subtitles' => $subtitles]);
    }

    /**
     * Obter URL da legenda
     */
    public function getSubtitleUrl(string $language): ?string
    {
        $subtitle = $this->subtitles[$language] ?? null;
        
        if (!$subtitle) {
            return null;
        }

        return Storage::url('subtitles/' . $subtitle['file_path']);
    }

    /**
     * Adicionar capítulo
     */
    public function addChapter(int $timeInSeconds, string $title, string $description = null): void
    {
        $chapters = $this->chapters ?? [];
        $chapters[] = [
            'id' => uniqid(),
            'time' => $timeInSeconds,
            'title' => $title,
            'description' => $description,
            'created_at' => now()->toISOString(),
        ];

        // Ordenar por tempo
        usort($chapters, fn($a, $b) => $a['time'] <=> $b['time']);

        $this->update(['chapters' => $chapters]);
    }

    /**
     * Obter capítulos ordenados
     */
    public function getOrderedChapters(): array
    {
        if (!$this->chapters) {
            return [];
        }

        $chapters = $this->chapters;
        usort($chapters, fn($a, $b) => $a['time'] <=> $b['time']);

        return $chapters;
    }

    /**
     * Registrar visualização
     */
    public function recordView(User $user, int $watchTimeSeconds, int $currentTimeSeconds): void
    {
        $this->viewProgress()->updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_video_id' => $this->id,
            ],
            [
                'watch_time_seconds' => $watchTimeSeconds,
                'current_time_seconds' => $currentTimeSeconds,
                'last_watched_at' => now(),
            ]
        );

        // Atualizar progresso da aula se necessário
        $progressPercentage = $this->duration_seconds > 0 
            ? min(100, round(($watchTimeSeconds / $this->duration_seconds) * 100))
            : 0;

        $this->lesson->userProgress()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'progress_percentage' => $progressPercentage,
                'watch_time_seconds' => $watchTimeSeconds,
                'last_watched_at' => now(),
            ]
        );
    }

    /**
     * Obter estatísticas de visualização
     */
    public function getViewStats(): array
    {
        $totalViews = $this->viewProgress()->count();
        $uniqueViewers = $this->viewProgress()->distinct('user_id')->count();
        $averageWatchTime = $this->viewProgress()->avg('watch_time_seconds') ?? 0;
        $completionRate = $this->duration_seconds > 0 
            ? $this->viewProgress()->where('watch_time_seconds', '>=', $this->duration_seconds * 0.8)->count()
            : 0;

        return [
            'total_views' => $totalViews,
            'unique_viewers' => $uniqueViewers,
            'average_watch_time' => round($averageWatchTime / 60, 1), // em minutos
            'completion_rate' => $uniqueViewers > 0 ? round(($completionRate / $uniqueViewers) * 100, 1) : 0,
            'engagement_points' => $this->calculateEngagementPoints($averageWatchTime),
        ];
    }

    /**
     * Calcular pontos de engajamento
     */
    private function calculateEngagementPoints(float $averageWatchTime): int
    {
        if (!$this->duration_seconds) {
            return 0;
        }

        $watchPercentage = ($averageWatchTime / $this->duration_seconds) * 100;
        
        return match(true) {
            $watchPercentage >= 90 => 100,
            $watchPercentage >= 75 => 75,
            $watchPercentage >= 50 => 50,
            $watchPercentage >= 25 => 25,
            default => 10,
        };
    }
}