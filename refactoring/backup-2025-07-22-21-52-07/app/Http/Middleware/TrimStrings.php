<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

/**
 * Middleware para remover espaços em branco dos dados de entrada.
 */
class TrimStrings extends Middleware
{
    /**
     * Manipula a requisição para remover espaços em branco.
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
