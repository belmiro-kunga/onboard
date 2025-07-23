<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasActiveStatus;
use App\Models\Traits\Orderable;
use App\Models\Traits\FormattedTimestamps;
use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $bio
 * ... outros campos relevantes ...
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'avatar',
        'bio',
        'phone',
        'birthdate',
        'department',
        'position',
        'hire_date',
        'last_login_at',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'hire_date' => 'date',
        'birthdate' => 'date',
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'preferences' => 'array',
        'password' => 'hashed',
    ];

    /**
     * Relacionamento: quizzes tentados pelo usuário.
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Relacionamento: progresso do usuário.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Relacionamento: inscrições em cursos
     */
    public function courseEnrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Relacionamento: cursos inscritos
     */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
                    ->withPivot(['enrolled_at', 'started_at', 'completed_at', 'progress_percentage', 'status', 'completion_data'])
                    ->withTimestamps();
    }

    /**
     * Relacionamento: cursos completados
     */
    public function completedCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
                    ->wherePivot('status', 'completed')
                    ->withPivot(['completed_at', 'progress_percentage', 'completion_data'])
                    ->withTimestamps();
    }

    /**
     * Relacionamento: gamificação do usuário.
     */
    public function gamification(): HasOne
    {
        return $this->hasOne(UserGamification::class);
    }

    /**
     * Relacionamento com módulos completados
     */
    public function completedModules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'user_progress')
                    ->wherePivot('status', 'completed')
                    ->withTimestamps();
    }

    /**
     * Relacionamento com notificações
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relacionamento com certificados
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Métodos de negócio
     */

    /**
     * Calcular percentual de progresso do onboarding
     */
    public function getProgressPercentage(): int
    {
        $totalModules = Module::where('is_active', true)->count();
        $completedModules = $this->completedModules()->count();
        
        if ($totalModules === 0) {
            return 0;
        }
        
        return (int) round(($completedModules / $totalModules) * 100);
    }

    /**
     * Obter nível atual do usuário
     */
    public function getCurrentLevel(): string
    {
        $gamification = $this->gamification;
        
        if (!$gamification) {
            return 'Iniciante';
        }
        
        return $gamification->current_level ?? 'Iniciante';
    }

    /**
     * Exemplo de método customizado tipado.
     */
    public function getTotalPoints(): int
    {
        return $this->gamification?->total_points ?? 0;
    }

    /**
     * Obter próximo módulo recomendado
     */
    public function getNextRecommendedModule(): ?string
    {
        $completedModuleIds = $this->completedModules()->pluck('modules.id');
        
        $nextModule = Module::where('is_active', true)
                           ->whereNotIn('id', $completedModuleIds)
                           ->orderBy('order_index')
                           ->first();
        
        return $nextModule ? $nextModule->title : null;
    }

    /**
     * Obter próximo módulo recomendado (objeto completo)
     */
    public function getNextRecommendedModuleObject(): ?Module
    {
        $completedModuleIds = $this->completedModules()->pluck('modules.id');
        
        return Module::where('is_active', true)
                    ->whereNotIn('id', $completedModuleIds)
                    ->orderBy('order_index')
                    ->first();
    }

    /**
     * Verificar se o usuário tem um papel específico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verificar se o usuário é gestor
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    /**
     * Verificar se o usuário está ativo
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
    

    
    /**
     * Relacionamento com histórico de pontos
     */
    public function pointsHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PointsHistory::class);
    }

    /**
     * Relacionamento com conquistas
     */
    public function achievements(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot(['earned_at', 'points_awarded'])
                    ->withTimestamps();
    }

    /**
     * Adicionar pontos ao usuário.
     *
     * @param int $points
     * @param string $reason
     * @return array
     */
    public function addPoints(int $points, string $reason = ''): array
    {
        $gamificationService = app(\App\Services\GamificationService::class);
        return $gamificationService->addPoints($this, $points, $reason);
    }

    /**
     * Verificar conquistas do usuário
     */
    public function checkAchievements(): array
    {
        $gamificationService = app(\App\Services\GamificationService::class);
        return $gamificationService->checkAchievements($this);
    }

    /**
     * Scope para usuários ativos.
     */
    // Scope Active disponível via trait


    /**
     * Scope para buscar por papel.
     */
    public function scopeByRole(\Illuminate\Database\Eloquent\Builder $query, string $role): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('role', $role);
    }

    /**
     * Accessor: nome formatado (primeira letra maiúscula).
     */
    public function getFormattedNameAttribute(): string
    {
        return ucfirst($this->name);
    }

    /**
     * Accessor: avatar customizado ou padrão.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        // Gerar avatar padrão baseado no nome
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Obter nome completo formatado
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    
    /**
     * Obter iniciais do nome
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Obter notificações não lidas
     */
    public function unreadNotifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Obter contagem de notificações não lidas
     */
    public function unreadNotificationsCount(): int
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Enviar uma notificação para o usuário
     */
    public function sendNotification(
        string $title,
        string $message,
        string $type = 'info',
        ?string $icon = null,
        ?string $color = null,
        ?string $link = null,
        array $data = []
    ) {
        $notificationService = app(\App\Services\NotificationService::class);
        return $notificationService->sendToUser(
            $this,
            $title,
            $message,
            $type,
            $icon,
            $color,
            $link,
            $data
        );
    }
}
