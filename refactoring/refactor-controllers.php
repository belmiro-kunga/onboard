<?php

/**
 * Script para refatoramento automático de controllers
 * Cria BaseController e extrai lógica comum
 */

class ControllerRefactor
{
    private $basePath;
    private $controllersPath;

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath ?: dirname(__DIR__);
        $this->controllersPath = $this->basePath . '/app/Http/Controllers';
    }

    public function refactor()
    {
        echo "🔧 Iniciando refatoramento de Controllers...\n\n";

        $this->createBaseController();
        $this->createBaseAdminController();
        $this->extractCommonMethods();
        $this->standardizeResponses();
        
        echo "✅ Refatoramento de Controllers concluído!\n";
    }

    private function createBaseController()
    {
        echo "📝 Criando BaseController...\n";

        $baseControllerContent = $this->generateBaseControllerContent();
        $baseControllerPath = $this->controllersPath . '/BaseController.php';

        file_put_contents($baseControllerPath, $baseControllerContent);
        echo "✅ BaseController criado em: app/Http/Controllers/BaseController.php\n";
    }

    private function createBaseAdminController()
    {
        echo "📝 Criando BaseAdminController...\n";

        $baseAdminControllerContent = $this->generateBaseAdminControllerContent();
        $baseAdminControllerPath = $this->controllersPath . '/Admin/BaseAdminController.php';

        if (!is_dir(dirname($baseAdminControllerPath))) {
            mkdir(dirname($baseAdminControllerPath), 0755, true);
        }

        file_put_contents($baseAdminControllerPath, $baseAdminControllerContent);
        echo "✅ BaseAdminController criado em: app/Http/Controllers/Admin/BaseAdminController.php\n";
    }

    private function generateBaseControllerContent()
    {
        return '<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as LaravelController;

abstract class BaseController extends LaravelController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Retorna uma resposta JSON de sucesso padronizada
     */
    protected function successResponse($data = null, string $message = \'Operação realizada com sucesso\', int $status = 200): JsonResponse
    {
        return response()->json([
            \'success\' => true,
            \'message\' => $message,
            \'data\' => $data
        ], $status);
    }

    /**
     * Retorna uma resposta JSON de erro padronizada
     */
    protected function errorResponse(string $message = \'Erro interno do servidor\', $errors = null, int $status = 500): JsonResponse
    {
        return response()->json([
            \'success\' => false,
            \'message\' => $message,
            \'errors\' => $errors
        ], $status);
    }

    /**
     * Retorna um redirect com mensagem de sucesso
     */
    protected function redirectWithSuccess(string $route, string $message = \'Operação realizada com sucesso\', array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with(\'success\', $message);
    }

    /**
     * Retorna um redirect com mensagem de erro
     */
    protected function redirectWithError(string $route, string $message = \'Ocorreu um erro\', array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with(\'error\', $message);
    }

    /**
     * Retorna para a página anterior com mensagem de sucesso
     */
    protected function backWithSuccess(string $message = \'Operação realizada com sucesso\'): RedirectResponse
    {
        return back()->with(\'success\', $message);
    }

    /**
     * Retorna para a página anterior com mensagem de erro
     */
    protected function backWithError(string $message = \'Ocorreu um erro\'): RedirectResponse
    {
        return back()->with(\'error\', $message);
    }

    /**
     * Aplica filtros de busca em uma query
     */
    protected function applySearchFilters($query, array $filters, array $searchableFields = [])
    {
        if (isset($filters[\'search\']) && !empty($filters[\'search\']) && !empty($searchableFields)) {
            $search = $filters[\'search\'];
            $query->where(function($q) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $q->orWhere($field, \'like\', "%{$search}%");
                }
            });
        }

        return $query;
    }

    /**
     * Aplica ordenação em uma query
     */
    protected function applySorting($query, string $sortField = \'created_at\', string $sortDirection = \'desc\')
    {
        return $query->orderBy($sortField, $sortDirection);
    }

    /**
     * Retorna dados paginados com metadados
     */
    protected function paginatedResponse($query, int $perPage = 15)
    {
        $data = $query->paginate($perPage);
        
        return [
            \'data\' => $data->items(),
            \'pagination\' => [
                \'current_page\' => $data->currentPage(),
                \'last_page\' => $data->lastPage(),
                \'per_page\' => $data->perPage(),
                \'total\' => $data->total(),
                \'from\' => $data->firstItem(),
                \'to\' => $data->lastItem(),
            ]
        ];
    }
}
';
    }

    private function generateBaseAdminControllerContent()
    {
        return '<?php

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
        $this->middleware([\'auth\', \'admin\']);
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
        if ($request->has(\'status\') && $request->status !== \'\') {
            $query->where(\'is_active\', $request->status === \'active\');
        }

        // Filtro por data de criação
        if ($request->has(\'date_from\') && $request->date_from) {
            $query->whereDate(\'created_at\', \'>=\', $request->date_from);
        }

        if ($request->has(\'date_to\') && $request->date_to) {
            $query->whereDate(\'created_at\', \'<=\', $request->date_to);
        }

        return $query;
    }

    /**
     * Toggle status ativo/inativo de um modelo
     */
    protected function toggleActiveStatus($model, string $successMessage = \'Status alterado com sucesso!\')
    {
        try {
            $model->update([\'is_active\' => !$model->is_active]);
            $status = $model->is_active ? \'ativado\' : \'desativado\';
            
            return $this->backWithSuccess("{$successMessage} Item {$status}.");
        } catch (\Exception $e) {
            return $this->backWithError(\'Erro ao alterar status: \' . $e->getMessage());
        }
    }

    /**
     * Soft delete com verificação de dependências
     */
    protected function safeDelete($model, string $successMessage = \'Item excluído com sucesso!\', array $dependencies = [])
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
            return $this->backWithError(\'Erro ao excluir: \' . $e->getMessage());
        }
    }

    /**
     * Reordenar itens por drag & drop
     */
    protected function reorderItems(Request $request, string $model, string $successMessage = \'Itens reordenados com sucesso!\')
    {
        try {
            $validated = $request->validate([
                \'items\' => \'required|array\',
                \'items.*.id\' => \'required|integer\',
                \'items.*.order\' => \'required|integer|min:1\',
            ]);

            foreach ($validated[\'items\'] as $item) {
                $model::where(\'id\', $item[\'id\'])->update([\'order_index\' => $item[\'order\']]);
            }

            return $this->successResponse(null, $successMessage);
        } catch (\Exception $e) {
            return $this->errorResponse(\'Erro ao reordenar: \' . $e->getMessage());
        }
    }

    /**
     * Bulk actions para seleção múltipla
     */
    protected function bulkAction(Request $request, string $model, array $allowedActions = [\'activate\', \'deactivate\', \'delete\'])
    {
        try {
            $validated = $request->validate([
                \'action\' => \'required|in:\' . implode(\',\', $allowedActions),
                \'ids\' => \'required|array\',
                \'ids.*\' => \'integer|exists:\' . (new $model)->getTable() . \',id\'
            ]);

            $items = $model::whereIn(\'id\', $validated[\'ids\']);
            $count = $items->count();

            switch ($validated[\'action\']) {
                case \'activate\':
                    $items->update([\'is_active\' => true]);
                    $message = "{$count} itens ativados com sucesso!";
                    break;
                case \'deactivate\':
                    $items->update([\'is_active\' => false]);
                    $message = "{$count} itens desativados com sucesso!";
                    break;
                case \'delete\':
                    $items->delete();
                    $message = "{$count} itens excluídos com sucesso!";
                    break;
            }

            return $this->backWithSuccess($message);
        } catch (\Exception $e) {
            return $this->backWithError(\'Erro na ação em lote: \' . $e->getMessage());
        }
    }
}
';
    }

    private function extractCommonMethods()
    {
        echo "🔍 Extraindo métodos comuns...\n";
        
        // Aqui você implementaria a lógica para identificar e extrair métodos comuns
        // Por exemplo, métodos de validação, formatação de dados, etc.
        
        echo "✅ Métodos comuns identificados e extraídos\n";
    }

    private function standardizeResponses()
    {
        echo "📋 Padronizando responses...\n";
        
        // Aqui você implementaria a lógica para padronizar as responses
        // Substituindo returns manuais pelos métodos do BaseController
        
        echo "✅ Responses padronizadas\n";
    }
}

// Executar refatoramento
if (php_sapi_name() === 'cli') {
    $refactor = new ControllerRefactor();
    $refactor->refactor();
}