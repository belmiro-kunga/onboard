<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleExpiredSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sessão expirada. Token CSRF renovado.',
                    'csrf_token' => csrf_token(),
                    'reload_required' => false
                ], 419);
            }

            // Para requisições normais, redireciona com mensagem amigável
            return redirect()->back()
                ->withInput($request->except(['_token', '_method', 'password', 'password_confirmation']))
                ->with('warning', 'Sua sessão expirou. Os dados foram preservados, tente novamente.');
        }
    }
}