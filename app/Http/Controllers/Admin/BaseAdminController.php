<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

abstract class BaseAdminController extends BaseController
{
    /**
     * Middleware padrão para controllers admin
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Retorna view admin com layout padrão
     */
    protected function adminView(string $view, array $data = [])
    {
        return view("admin.{$view}", $data);
    }

    /**
     * Aplica filtros comuns para listagens admin
     */
    protected function applyAdminFilters($query, Request $request)
    {
        // Filtro por status ativo/inativo
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filtro por data de criação
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    /**
     * Toggle status ativo/inativo de um modelo
     */
    protected function toggleActiveStatus($model, string $successMessage = 'Status alterado com sucesso!')
    {
        try {
            $model->update(['is_active' => !$model->is_active]);
            $status = $model->is_active ? 'ativado' : 'desativado';
            
            return $this->backWithSuccess("{$successMessage} Item {$status}.");
        } catch (\Exception $e) {
            return $this->backWithError('Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete com verificação de dependências
     */
    protected function safeDelete($model, string $successMessage = 'Item excluído com sucesso!', array $dependencies = [])
    {
        try {
            // Verificar dependências
            foreach ($dependencies as $relation => $message) {
                if ($model->$relation()->exists()) {
                    return $this->backWithError($message);
                }
            }

            $model->delete();
            return $this->backWithSuccess($successMessage);
        } catch (\Exception $e) {
            return $this->backWithError('Erro ao excluir: ' . $e->getMessage());
        }
    }

    /**
     * Reordenar itens por drag & drop
     */
    protected function reorderItems(Request $request, string $model, string $successMessage = 'Itens reordenados com sucesso!')
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'required|integer',
                'items.*.order' => 'required|integer|min:1',
            ]);

            foreach ($validated['items'] as $item) {
                $model::where('id', $item['id'])->update(['order_index' => $item['order']]);
            }

            return $this->successResponse(null, $successMessage);
        } catch (\Exception $e) {
            return $this->errorResponse('Erro ao reordenar: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions para seleção múltipla
     */
    protected function bulkAction(Request $request, string $model, array $allowedActions = ['activate', 'deactivate', 'delete'])
    {
        try {
            $validated = $request->validate([
                'action' => 'required|in:' . implode(',', $allowedActions),
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:' . (new $model)->getTable() . ',id'
            ]);

            $items = $model::whereIn('id', $validated['ids']);
            $count = $items->count();

            switch ($validated['action']) {
                case 'activate':
                    $items->update(['is_active' => true]);
                    $message = "{$count} itens ativados com sucesso!";
                    break;
                case 'deactivate':
                    $items->update(['is_active' => false]);
                    $message = "{$count} itens desativados com sucesso!";
                    break;
                case 'delete':
                    $items->delete();
                    $message = "{$count} itens excluídos com sucesso!";
                    break;
            }

            return $this->backWithSuccess($message);
        } catch (\Exception $e) {
            return $this->backWithError('Erro na ação em lote: ' . $e->getMessage());
        }
    }

    /**
     * Método base para listagem com filtros padrão
     */
    protected function baseIndex($model, Request $request, array $searchFields = [], array $relations = [])
    {
        $query = $model::query();

        // Aplicar eager loading se especificado
        if (!empty($relations)) {
            $query->with($relations);
        }

        // Aplicar filtros admin padrão
        $query = $this->applyAdminFilters($query, $request);
        
        // Aplicar busca se especificada
        if (!empty($searchFields)) {
            $query = $this->applySearchFilters($query, $request->all(), $searchFields);
        }

        // Aplicar ordenação
        $sortField = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query = $this->applySorting($query, $sortField, $sortDirection);

        // Paginação
        $items = $query->paginate(15)->withQueryString();

        return $items;
    }

    /**
     * Gerar estatísticas padrão para listagens
     */
    protected function generateStats($model)
    {
        $stats = [
            'total' => $model::count(),
        ];

        // Adicionar estatísticas de status se o model tem is_active
        if (method_exists($model, 'scopeActive')) {
            $stats['active'] = $model::active()->count();
            $stats['inactive'] = $model::where('is_active', false)->count();
        }

        return $stats;
    }
}
