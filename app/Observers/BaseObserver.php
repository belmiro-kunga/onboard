<?php

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
