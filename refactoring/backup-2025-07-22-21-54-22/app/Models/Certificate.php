<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * ... outros campos relevantes ...
 */
class Certificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'quiz_id',
        'quiz_attempt_id',
        'certificate_number',
        'type',
        'title',
        'description',
        'category',
        'reference_id',
        'reference_type',
        'issued_at',
        'expires_at',
        'revoked_at',
        'valid_until',
        'verification_code',
        'score',
        'metadata',
        'file_path',
        'file_size',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'valid_until' => 'datetime',
        'score' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento: usuário dono do certificado.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com quiz
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relacionamento com tentativa
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    /**
     * Relacionamento com tentativa (alias)
     */
    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    /**
     * Verificar se o certificado é válido
     */
    public function isValid(): bool
    {
        if (!$this->valid_until) {
            return true; // Certificado sem expiração
        }

        return $this->valid_until->isFuture();
    }

    /**
     * Obter status do certificado
     */
    public function getStatusAttribute(): string
    {
        return $this->isValid() ? 'Válido' : 'Expirado';
    }

    /**
     * Obter cor do status
     */
    public function getStatusColorAttribute(): string
    {
        return $this->isValid() ? 'green' : 'red';
    }

    /**
     * Scope para certificados válidos.
     */
    public function scopeValid(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where(function($q) {
            $q->whereNull('valid_until')->orWhere('valid_until', '>', now());
        });
    }

    /**
     * Verifica se o certificado está expirado.
     */
    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until < now();
    }
}