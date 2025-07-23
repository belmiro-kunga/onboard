<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\FormattedTimestamps;
use App\Models\Traits\HasCommonScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnrollment extends Model
{
    use HasFactory, FormattedTimestamps, HasCommonScopes;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'started_at',
        'completed_at',
        'progress_percentage',
        'status',
        'completion_data',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'integer',
        'completion_data' => 'array',
    ];

    /**
     * Relacionamentos
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scopes
     */
    // Scope Completed disponível via trait HasCommonScopes


    // Scope InProgress disponível via trait HasCommonScopes


    // Scope ByStatus disponível via trait HasCommonScopes


    /**
     * Accessors
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'enrolled' => 'Inscrito',
            'in_progress' => 'Em Progresso',
            'completed' => 'Concluído',
            'dropped' => 'Abandonado',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'enrolled' => 'blue',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'dropped' => 'red',
            default => 'gray'
        };
    }

    /**
     * Métodos auxiliares
     */
    public function markAsStarted()
    {
        $this->update([
            'started_at' => now(),
            'status' => 'in_progress'
        ]);
    }

    public function markAsCompleted($completionData = [])
    {
        $this->update([
            'completed_at' => now(),
            'status' => 'completed',
            'progress_percentage' => 100,
            'completion_data' => $completionData
        ]);
    }

    public function updateProgress($percentage)
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'status' => $percentage >= 100 ? 'completed' : 'in_progress'
        ]);

        if ($percentage >= 100 && !$this->completed_at) {
            $this->markAsCompleted();
        }
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at) return null;
        
        $endDate = $this->completed_at ?? now();
        return $this->started_at->diffInDays($endDate);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isDropped()
    {
        return $this->status === 'dropped';
    }
}