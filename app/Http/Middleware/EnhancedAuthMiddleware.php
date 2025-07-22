<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Responses\WebResponse;

class EnhancedAuthMiddleware
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $guard = null)
    {
        if ($this->authService->needsLogin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            return WebResponse::redirectToLogin('Você precisa estar logado para acessar esta página');
        }

        if (!$this->authService->isActiveUser()) {
            $this->authService->logout();
            
            return WebResponse::redirectToLogin('Sua conta foi desativada. Entre em contato com o administrador.');
        }

        return $next($request);
    }
}
