<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    protected function getCachePrefix(): string
    {
        return strtolower(class_basename($this)) . '_';
    }

    public function getCacheKey(string $suffix = ''): string
    {
        $key = $this->getCachePrefix() . $this->getKey();
        return $suffix ? $key . '_' . $suffix : $key;
    }

    public function cache(string $suffix = '', int $minutes = 60): self
    {
        Cache::put($this->getCacheKey($suffix), $this, now()->addMinutes($minutes));
        return $this;
    }

    public static function remember(string $key, int $minutes, \Closure $callback)
    {
        return Cache::remember($key, now()->addMinutes($minutes), $callback);
    }

    public function forgetCache(string $suffix = ''): bool
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
