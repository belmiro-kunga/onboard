<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

/**
 * Middleware para prevenir requisições durante manutenção.
 */
class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * Manipula a requisição durante manutenção.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, \Closure $next)
    {
        return parent::handle($request, $next);
    }
}
