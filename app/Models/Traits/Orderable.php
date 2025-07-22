<?php

namespace App\Models\Traits;

trait Orderable
{
    /**
     * Scope para ordenar por order_index
     */
    public function scopeOrdered($query, string $direction = 'asc')
    {
        return $query->orderBy('order_index', $direction);
    }

    /**
     * Scope para ordenar por título
     */
    public function scopeOrderedByTitle($query, string $direction = 'asc')
    {
        return $query->orderBy('title', $direction);
    }

    /**
     * Scope para ordenar por data de criação
     */
    public function scopeOrderedByDate($query, string $direction = 'desc')
    {
        return $query->orderBy('created_at', $direction);
    }

    /**
     * Move o item para cima na ordem
     */
    public function moveUp(): bool
    {
        $previous = static::where('order_index', '<', $this->order_index)
            ->orderBy('order_index', 'desc')
            ->first();

        if ($previous) {
            $tempOrder = $this->order_index;
            $this->update(['order_index' => $previous->order_index]);
            $previous->update(['order_index' => $tempOrder]);
            return true;
        }

        return false;
    }

    /**
     * Move o item para baixo na ordem
     */
    public function moveDown(): bool
    {
        $next = static::where('order_index', '>', $this->order_index)
            ->orderBy('order_index', 'asc')
            ->first();

        if ($next) {
            $tempOrder = $this->order_index;
            $this->update(['order_index' => $next->order_index]);
            $next->update(['order_index' => $tempOrder]);
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
                $model->order_index = (static::max('order_index') ?? 0) + 1;
            }
        });
    }
}
