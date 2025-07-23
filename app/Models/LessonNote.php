<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para notas pessoais dos usuários em aulas
 * 
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property string $content
 * @property int|null $video_timestamp
 * @property string|null $color
 * @property array|null $metadata
 */
class LessonNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'content',
        'video_timestamp',
        'color',
        'metadata',
    ];

    protected $casts = [
        'video_timestamp' => 'integer',
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
     * Relacionamento com aula
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Obter timestamp formatado
     */
    public function getFormattedTimestampAttribute(): ?string
    {
        if (!$this->video_timestamp) {
            return null;
        }

        $minutes = floor($this->video_timestamp / 60);
        $seconds = $this->video_timestamp % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Obter cor padrão se não definida
     */
    public function getColorAttribute($value): string
    {
        return $value ?? 'yellow';
    }

    /**
     * Scopes
     */
    public function scopeWithTimestamp($query)
    {
        return $query->whereNotNull('video_timestamp');
    }

    public function scopeByColor($query, string $color)
    {
        return $query->where('color', $color);
    }
}