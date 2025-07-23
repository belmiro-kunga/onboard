<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model para comentários em aulas
 * 
 * @property int $id
 * @property int $lesson_id
 * @property int $user_id
 * @property int|null $parent_id
 * @property string $content
 * @property int|null $video_timestamp
 * @property bool $is_question
 * @property bool $is_resolved
 * @property int $likes_count
 * @property array|null $metadata
 */
class LessonComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lesson_id',
        'user_id',
        'parent_id',
        'content',
        'video_timestamp',
        'is_question',
        'is_resolved',
        'likes_count',
        'metadata',
    ];

    protected $casts = [
        'video_timestamp' => 'integer',
        'is_question' => 'boolean',
        'is_resolved' => 'boolean',
        'likes_count' => 'integer',
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
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com comentário pai
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Relacionamento com respostas
     */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    /**
     * Relacionamento com likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(LessonCommentLike::class);
    }

    /**
     * Verificar se é resposta
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Verificar se é comentário principal
     */
    public function isMainComment(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Verificar se usuário curtiu o comentário
     */
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Curtir comentário
     */
    public function likeBy(User $user): void
    {
        if (!$this->isLikedBy($user)) {
            $this->likes()->create(['user_id' => $user->id]);
            $this->increment('likes_count');
        }
    }

    /**
     * Descurtir comentário
     */
    public function unlikeBy(User $user): void
    {
        if ($this->isLikedBy($user)) {
            $this->likes()->where('user_id', $user->id)->delete();
            $this->decrement('likes_count');
        }
    }

    /**
     * Marcar pergunta como resolvida
     */
    public function markAsResolved(): void
    {
        if ($this->is_question) {
            $this->update(['is_resolved' => true]);
        }
    }

    /**
     * Marcar pergunta como não resolvida
     */
    public function markAsUnresolved(): void
    {
        if ($this->is_question) {
            $this->update(['is_resolved' => false]);
        }
    }

    /**
     * Obter timestamp formatado do vídeo
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
     * Obter URL do timestamp no vídeo
     */
    public function getTimestampUrlAttribute(): ?string
    {
        if (!$this->video_timestamp) {
            return null;
        }

        return route('lessons.show', [
            'lesson' => $this->lesson_id,
            't' => $this->video_timestamp
        ]);
    }

    /**
     * Obter tipo do comentário
     */
    public function getTypeAttribute(): string
    {
        if ($this->is_question) {
            return $this->is_resolved ? 'question_resolved' : 'question';
        }

        return 'comment';
    }

    /**
     * Obter ícone do tipo
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'question' => 'help-circle',
            'question_resolved' => 'check-circle',
            default => 'message-circle',
        };
    }

    /**
     * Obter cor do tipo
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'question' => 'orange',
            'question_resolved' => 'green',
            default => 'blue',
        };
    }

    /**
     * Verificar se pode ser editado pelo usuário
     */
    public function canBeEditedBy(User $user): bool
    {
        // Autor pode editar por 15 minutos
        if ($this->user_id === $user->id) {
            return $this->created_at->isAfter(now()->subMinutes(15));
        }

        // Moderadores/admins podem editar sempre
        return $user->hasRole('admin') || $user->hasRole('moderator');
    }

    /**
     * Verificar se pode ser deletado pelo usuário
     */
    public function canBeDeletedBy(User $user): bool
    {
        // Autor pode deletar
        if ($this->user_id === $user->id) {
            return true;
        }

        // Moderadores/admins podem deletar
        return $user->hasRole('admin') || $user->hasRole('moderator');
    }

    /**
     * Obter thread completa (comentário + respostas)
     */
    public function getThread(): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->isReply()) {
            return $this->parent->getThread();
        }

        return collect([$this])->merge($this->replies()->with('user', 'likes')->get());
    }

    /**
     * Scopes
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeQuestions($query)
    {
        return $query->where('is_question', true);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_question', true)
                    ->where('is_resolved', true);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_question', true)
                    ->where('is_resolved', false);
    }

    public function scopeWithTimestamp($query)
    {
        return $query->whereNotNull('video_timestamp');
    }

    public function scopePopular($query)
    {
        return $query->where('likes_count', '>', 0)
                    ->orderBy('likes_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}