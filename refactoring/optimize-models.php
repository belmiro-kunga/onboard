<?php

/**
 * Script para otimização de Models
 * Cria traits, scopes e otimiza relacionamentos
 */

class ModelOptimizer
{
    private $basePath;
    private $modelsPath;

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath ?: dirname(__DIR__);
        $this->modelsPath = $this->basePath . '/app/Models';
    }

    public function optimize()
    {
        echo "🔧 Iniciando otimização de Models...\n\n";

        $this->createCommonTraits();
        $this->createGlobalScopes();
        $this->optimizeRelationships();
        $this->createObservers();
        
        echo "✅ Otimização de Models concluída!\n";
    }

    private function createCommonTraits()
    {
        echo "📝 Criando traits comuns...\n";

        $this->createActiveTrait();
        $this->createOrderableTrait();
        $this->createTimestampsTrait();
        
        echo "✅ Traits criadas\n";
    }

    private function createActiveTrait()
    {
        $traitPath = $this->basePath . '/app/Models/Traits';
        if (!is_dir($traitPath)) {
            mkdir($traitPath, 0755, true);
        }

        $activeTraitContent = '<?php

namespace App\Models\Traits;

trait HasActiveStatus
{
    /**
     * Scope para filtrar apenas registros ativos
     */
    public function scopeActive($query)
    {
        return $query->where(\'is_active\', true);
    }

    /**
     * Scope para filtrar apenas registros inativos
     */
    public function scopeInactive($query)
    {
        return $query->where(\'is_active\', false);
    }

    /**
     * Verifica se o registro está ativo
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Verifica se o registro está inativo
     */
    public function isInactive(): bool
    {
        return $this->is_active === false;
    }

    /**
     * Ativa o registro
     */
    public function activate(): bool
    {
        return $this->update([\'is_active\' => true]);
    }

    /**
     * Desativa o registro
     */
    public function deactivate(): bool
    {
        return $this->update([\'is_active\' => false]);
    }

    /**
     * Alterna o status ativo/inativo
     */
    public function toggleActive(): bool
    {
        return $this->update([\'is_active\' => !$this->is_active]);
    }
}
';

        file_put_contents($traitPath . '/HasActiveStatus.php', $activeTraitContent);
    }

    private function createOrderableTrait()
    {
        $traitPath = $this->basePath . '/app/Models/Traits';
        
        $orderableTraitContent = '<?php

namespace App\Models\Traits;

trait Orderable
{
    /**
     * Scope para ordenar por order_index
     */
    public function scopeOrdered($query, string $direction = \'asc\')
    {
        return $query->orderBy(\'order_index\', $direction);
    }

    /**
     * Scope para ordenar por título
     */
    public function scopeOrderedByTitle($query, string $direction = \'asc\')
    {
        return $query->orderBy(\'title\', $direction);
    }

    /**
     * Scope para ordenar por data de criação
     */
    public function scopeOrderedByDate($query, string $direction = \'desc\')
    {
        return $query->orderBy(\'created_at\', $direction);
    }

    /**
     * Move o item para cima na ordem
     */
    public function moveUp(): bool
    {
        $previous = static::where(\'order_index\', \'<\', $this->order_index)
            ->orderBy(\'order_index\', \'desc\')
            ->first();

        if ($previous) {
            $tempOrder = $this->order_index;
            $this->update([\'order_index\' => $previous->order_index]);
            $previous->update([\'order_index\' => $tempOrder]);
            return true;
        }

        return false;
    }

    /**
     * Move o item para baixo na ordem
     */
    public function moveDown(): bool
    {
        $next = static::where(\'order_index\', \'>\', $this->order_index)
            ->orderBy(\'order_index\', \'asc\')
            ->first();

        if ($next) {
            $tempOrder = $this->order_index;
            $this->update([\'order_index\' => $next->order_index]);
            $next->update([\'order_index\' => $tempOrder]);
            return true;
        }

        return false;
    }

    /**
     * Define a ordem automaticamente ao criar
     */
    protected static function bootOrderable()
    {
        static::creating(function ($model) {
            if (empty($model->order_index)) {
                $model->order_index = (static::max(\'order_index\') ?? 0) + 1;
            }
        });
    }
}
';

        file_put_contents($traitPath . '/Orderable.php', $orderableTraitContent);
    }

    private function createTimestampsTrait()
    {
        $traitPath = $this->basePath . '/app/Models/Traits';
        
        $timestampsTraitContent = '<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait FormattedTimestamps
{
    /**
     * Retorna a data de criação formatada
     */
    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at ? $this->created_at->format(\'d/m/Y H:i\') : \'-\';
    }

    /**
     * Retorna a data de atualização formatada
     */
    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->format(\'d/m/Y H:i\') : \'-\';
    }

    /**
     * Retorna a data de criação em formato humano
     */
    public function getCreatedAtHumanAttribute(): string
    {
        return $this->created_at ? $this->created_at->diffForHumans() : \'-\';
    }

    /**
     * Retorna a data de atualização em formato humano
     */
    public function getUpdatedAtHumanAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->diffForHumans() : \'-\';
    }

    /**
     * Scope para filtrar por período
     */
    public function scopeCreatedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween(\'created_at\', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    /**
     * Scope para filtrar registros recentes
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where(\'created_at\', \'>=\', Carbon::now()->subDays($days));
    }
}
';

        file_put_contents($traitPath . '/FormattedTimestamps.php', $timestampsTraitContent);
    }

    private function createGlobalScopes()
    {
        echo "📝 Criando Global Scopes...\n";

        $scopesPath = $this->basePath . '/app/Models/Scopes';
        if (!is_dir($scopesPath)) {
            mkdir($scopesPath, 0755, true);
        }

        $activeScopeContent = '<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where(\'is_active\', true);
    }
}
';

        file_put_contents($scopesPath . '/ActiveScope.php', $activeScopeContent);
    }

    private function optimizeRelationships()
    {
        echo "🔍 Otimizando relacionamentos...\n";
        
        // Criar arquivo com relacionamentos otimizados
        $relationshipsPath = $this->basePath . '/app/Models/Concerns';
        if (!is_dir($relationshipsPath)) {
            mkdir($relationshipsPath, 0755, true);
        }

        $relationshipsConcernContent = '<?php

namespace App\Models\Concerns;

/**
 * Trait com relacionamentos otimizados para evitar N+1 queries
 */
trait OptimizedRelationships
{
    /**
     * Carrega relacionamentos com eager loading otimizado
     */
    public function scopeWithOptimizedRelations($query, array $relations = [])
    {
        if (empty($relations)) {
            // Relacionamentos padrão baseados no modelo
            $relations = $this->getDefaultEagerLoad();
        }

        return $query->with($relations);
    }

    /**
     * Relacionamentos padrão para eager loading
     */
    protected function getDefaultEagerLoad(): array
    {
        return [];
    }

    /**
     * Carrega relacionamentos apenas se necessário
     */
    public function loadRelationIfNeeded(string $relation)
    {
        if (!$this->relationLoaded($relation)) {
            $this->load($relation);
        }
        
        return $this;
    }

    /**
     * Conta relacionamentos sem carregar os dados
     */
    public function scopeWithCounts($query, array $relations = [])
    {
        foreach ($relations as $relation) {
            $query->withCount($relation);
        }
        
        return $query;
    }
}
';

        file_put_contents($relationshipsPath . '/OptimizedRelationships.php', $relationshipsConcernContent);
        
        echo "✅ Relacionamentos otimizados\n";
    }

    private function createObservers()
    {
        echo "📝 Criando Observers...\n";

        $observersPath = $this->basePath . '/app/Observers';
        if (!is_dir($observersPath)) {
            mkdir($observersPath, 0755, true);
        }

        // Observer base
        $baseObserverContent = '<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

abstract class BaseObserver
{
    /**
     * Handle the model "creating" event.
     */
    public function creating(Model $model): void
    {
        $this->setDefaults($model);
        $this->validateBeforeCreate($model);
    }

    /**
     * Handle the model "created" event.
     */
    public function created(Model $model): void
    {
        $this->afterCreate($model);
    }

    /**
     * Handle the model "updating" event.
     */
    public function updating(Model $model): void
    {
        $this->validateBeforeUpdate($model);
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated(Model $model): void
    {
        $this->afterUpdate($model);
    }

    /**
     * Handle the model "deleting" event.
     */
    public function deleting(Model $model): void
    {
        $this->validateBeforeDelete($model);
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->afterDelete($model);
    }

    /**
     * Define valores padrão antes de criar
     */
    protected function setDefaults(Model $model): void
    {
        // Override in child classes
    }

    /**
     * Validações antes de criar
     */
    protected function validateBeforeCreate(Model $model): void
    {
        // Override in child classes
    }

    /**
     * Ações após criar
     */
    protected function afterCreate(Model $model): void
    {
        // Override in child classes
    }

    /**
     * Validações antes de atualizar
     */
    protected function validateBeforeUpdate(Model $model): void
    {
        // Override in child classes
    }

    /**
     * Ações após atualizar
     */
    protected function afterUpdate(Model $model): void
    {
        // Override in child classes
    }

    /**
     * Validações antes de deletar
     */
    protected function validateBeforeDelete(Model $model): void
    {
        // Override in child classes
    }

    /**
     * Ações após deletar
     */
    protected function afterDelete(Model $model): void
    {
        // Override in child classes
    }
}
';

        file_put_contents($observersPath . '/BaseObserver.php', $baseObserverContent);
        
        echo "✅ Observers criados\n";
    }
}

// Executar otimização
if (php_sapi_name() === 'cli') {
    $optimizer = new ModelOptimizer();
    $optimizer->optimize();
}