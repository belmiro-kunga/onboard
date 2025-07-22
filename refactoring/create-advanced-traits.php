<?php

/**
 * Script para criar traits avançados
 */

echo "📊 Criando traits avançados...\n";

// Criar HasCommonScopes trait
$traitPath = dirname(__DIR__) . '/app/Models/Traits';

$scopeTraitContent = '<?php

namespace App\Models\Traits;

trait HasCommonScopes
{
    public function scopeByType($query, string $type)
    {
        return $query->where(\'type\', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where(\'status\', $status);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where(\'category\', $category);
    }

    public function scopeValid($query)
    {
        return $query->where(\'is_valid\', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where(\'status\', \'completed\');
    }

    public function scopeInProgress($query)
    {
        return $query->where(\'status\', \'in_progress\');
    }

    public function scopeSearch($query, string $term, array $fields = [\'title\', \'name\', \'description\'])
    {
        return $query->where(function($q) use ($term, $fields) {
            foreach ($fields as $field) {
                $q->orWhere($field, \'like\', "%{$term}%");
            }
        });
    }
}
';

file_put_contents($traitPath . '/HasCommonScopes.php', $scopeTraitContent);
echo "  ✅ HasCommonScopes trait criado\n";

// Criar Cacheable trait
$cacheableTraitContent = '<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    protected function getCachePrefix(): string
    {
        return strtolower(class_basename($this)) . \'_\';
    }

    public function getCacheKey(string $suffix = \'\'): string
    {
        $key = $this->getCachePrefix() . $this->getKey();
        return $suffix ? $key . \'_\' . $suffix : $key;
    }

    public function cache(string $suffix = \'\', int $minutes = 60): self
    {
        Cache::put($this->getCacheKey($suffix), $this, now()->addMinutes($minutes));
        return $this;
    }

    public static function remember(string $key, int $minutes, \Closure $callback)
    {
        return Cache::remember($key, now()->addMinutes($minutes), $callback);
    }

    public function forgetCache(string $suffix = \'\'): bool
    {
        return Cache::forget($this->getCacheKey($suffix));
    }

    protected static function bootCacheable(): void
    {
        static::saved(function ($model) {
            $model->forgetCache();
        });

        static::deleted(function ($model) {
            $model->forgetCache();
        });
    }
}
';

file_put_contents($traitPath . '/Cacheable.php', $cacheableTraitContent);
echo "  ✅ Cacheable trait criado\n";

echo "✅ Traits avançados criados com sucesso!\n";