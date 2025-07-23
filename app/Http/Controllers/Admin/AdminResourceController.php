<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

abstract class AdminResourceController extends BaseAdminController
{
    protected string $modelClass;
    protected string $viewPrefix;
    protected array $searchFields = [];
    protected array $relations = [];
    protected string $resourceName;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = $this->baseIndex($this->modelClass, $request, $this->searchFields, $this->relations);
        $stats = $this->generateStats($this->modelClass);
        
        return $this->adminView("{$this->viewPrefix}.index", compact("items", "stats"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->adminView("{$this->viewPrefix}.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        
        $item = $this->modelClass::create($validated);
        
        return $this->redirectWithSuccess(
            "admin.{$this->viewPrefix}.show", 
            "{$this->resourceName} criado com sucesso!",
            [$item]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Model $item)
    {
        if (!empty($this->relations)) {
            $item->load($this->relations);
        }
        
        return $this->adminView("{$this->viewPrefix}.show", compact("item"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Model $item)
    {
        return $this->adminView("{$this->viewPrefix}.edit", compact("item"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Model $item)
    {
        $validated = $this->validateRequest($request, $item->id);
        
        $item->update($validated);
        
        return $this->redirectWithSuccess(
            "admin.{$this->viewPrefix}.show",
            "{$this->resourceName} atualizado com sucesso!",
            [$item]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Model $item)
    {
        $dependencies = $this->getDestroyDependencies();
        
        return $this->safeDelete($item, "{$this->resourceName} excluído com sucesso!", $dependencies);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Model $item)
    {
        return $this->toggleActiveStatus($item, "Status do {$this->resourceName} alterado com sucesso!");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request, string $model = null, array $allowedActions = ['activate', 'deactivate', 'delete'])
    {
        $modelClass = $model ?? $this->modelClass;
        return parent::bulkAction($request, $modelClass, $allowedActions);
    }

    /**
     * Get validation rules (deve ser implementado nas classes filhas)
     */
    abstract protected function getValidationRules(int $id = null): array;

    /**
     * Get validation messages (pode ser sobrescrito)
     */
    protected function getValidationMessages(): array
    {
        return [];
    }

    /**
     * Get dependencies for safe delete (pode ser sobrescrito)
     */
    protected function getDestroyDependencies(): array
    {
        return [];
    }

    /**
     * Validate request data
     */
    protected function validateRequest(Request $request, int $id = null): array
    {
        return $request->validate(
            $this->getValidationRules($id),
            $this->getValidationMessages()
        );
    }

    /**
     * Process data before save (pode ser sobrescrito)
     */
    protected function processData(array $data): array
    {
        return $data;
    }
}
