<?php

namespace App\Models\Traits;

trait HasCommonScopes
{
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeValid($query)
    {
        return $query->where('is_valid', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeSearch($query, string $term, array $fields = ['title', 'name', 'description'])
    {
        return $query->where(function($q) use ($term, $fields) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'like', "%{$term}%");
            }
        });
    }
}
