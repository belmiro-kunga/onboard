<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para garantir que o usuário possui o papel necessário.
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, \Closure $next, string $role)
    {
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Acesso negado: papel insuficiente.');
        }

        return $next($request);
    }
}