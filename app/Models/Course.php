<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Traits\HasActiveStatus;
use App\Models\Traits\Orderable;
use App\Models\Traits\FormattedTimestamps;
use App\Models\Traits\Cacheable;

class Course extends Model
{
    use HasFactory, HasActiveStatus, Orderable, FormattedTimestamps, Cacheable;

    protected $fillable = [
        'title',
        'description',
        'short_description',
        'thumbnail',
        'requirements',
        'duration_hours',
        'difficulty_level',
        'type',
        'is_active',
        'order_index',
        'tags',
    ];

    protected $casts = [
        'requirements' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
        'duration_hours' => 'integer',
        'order_index' => 'integer',
    ];

    /**
     * Relacionamento com módulos
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order_index');
    }

    /**
     * Relacionamento com inscrições
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Relacionamento com usuários inscritos
     */
    public function enrolledUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments')
                    ->withPivot(['enrolled_at', 'started_at', 'completed_at', 'progress_percentage', 'status', 'completion_data'])
                    ->withTimestamps();
    }

    /**
     * Relacionamento com usuários que completaram o curso
     */
    public function completedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments')
                    ->wherePivot('status', 'completed')
                    ->withPivot(['completed_at', 'progress_percentage', 'completion_data'])
                    ->withTimestamps();
    }

    /**
     * Scopes
     */
    // Scope Active disponível via trait


    // Scope ByType disponível via trait HasCommonScopes


    // Scope Ordered disponível via trait


    /**
     * Accessors
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        
        return asset('images/course-default.jpg');
    }

    public function getDifficultyLabelAttribute()
    {
        return match($this->difficulty_level) {
            'beginner' => 'Iniciante',
            'intermediate' => 'Intermediário',
            'advanced' => 'Avançado',
            default => 'Não definido'
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'mandatory' => 'Obrigatório',
            'optional' => 'Opcional',
            'certification' => 'Certificação',
            default => 'Não definido'
        };
    }

    /**
     * Métodos auxiliares
     */
    public function getTotalModules()
    {
        return $this->modules()->count();
    }

    public function getActiveModules()
    {
        return $this->modules()->where('is_active', true)->count();
    }

    public function getTotalEnrollments()
    {
        return $this->enrollments()->count();
    }

    public function getCompletionRate()
    {
        $total = $this->getTotalEnrollments();
        if ($total === 0) return 0;
        
        $completed = $this->enrollments()->where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }

    public function canUserEnroll(User $user)
    {
        // Verificar se já está inscrito
        if ($this->enrollments()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Verificar pré-requisitos
        if (!empty($this->requirements)) {
            foreach ($this->requirements as $requirement) {
                if (!$this->checkRequirement($user, $requirement)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function checkRequirement(User $user, array $requirement)
    {
        switch ($requirement['type']) {
            case 'course':
                return $user->completedCourses()->where('course_id', $requirement['course_id'])->exists();
            case 'role':
                return $user->role === $requirement['role'];
            case 'department':
                return $user->department === $requirement['department'];
            default:
                return true;
        }
    }

    public function getProgressForUser(User $user)
    {
        $enrollment = $this->enrollments()->where('user_id', $user->id)->first();
        return $enrollment ? $enrollment->progress_percentage : 0;
    }

    public function getStatusForUser(User $user)
    {
        $enrollment = $this->enrollments()->where('user_id', $user->id)->first();
        return $enrollment ? $enrollment->status : null;
    }
}