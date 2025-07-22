<?php

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
    protected function successResponse($data = null, string $message = 'Operação realizada com sucesso', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Retorna uma resposta JSON de erro padronizada
     */
    protected function errorResponse(string $message = 'Erro interno do servidor', $errors = null, int $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    /**
     * Retorna um redirect com mensagem de sucesso
     */
    protected function redirectWithSuccess(string $route, string $message = 'Operação realizada com sucesso', array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with('success', $message);
    }

    /**
     * Retorna um redirect com mensagem de erro
     */
    protected function redirectWithError(string $route, string $message = 'Ocorreu um erro', array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with('error', $message);
    }

    /**
     * Retorna para a página anterior com mensagem de sucesso
     */
    protected function backWithSuccess(string $message = 'Operação realizada com sucesso'): RedirectResponse
    {
        return back()->with('success', $message);
    }

    /**
     * Retorna para a página anterior com mensagem de erro
     */
    protected function backWithError(string $message = 'Ocorreu um erro'): RedirectResponse
    {
        return back()->with('error', $message);
    }

    /**
     * Aplica filtros de busca em uma query
     */
    protected function applySearchFilters($query, array $filters, array $searchableFields = [])
    {
        if (isset($filters['search']) && !empty($filters['search']) && !empty($searchableFields)) {
            $search = $filters['search'];
            $query->where(function($q) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        return $query;
    }

    /**
     * Aplica ordenação em uma query
     */
    protected function applySorting($query, string $sortField = 'created_at', string $sortDirection = 'desc')
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
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ];
    }
}
