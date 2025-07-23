<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para likes em comentários de aulas
 * 
 * @property int $id
 * @property int $lesson_comment_id
 * @property int $user_id
 */
class LessonCommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_comment_id',
        'user_id',
    ];

    public $timestamps = true;

    /**
     * Relacionamento com comentário
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(LessonComment::class, 'lesson_comment_id');
    }

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}