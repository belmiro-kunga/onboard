<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

/**
 * Middleware para autenticação de usuários.
 */
class Authenticate extends Middleware
{
    /**
     * Manipula a requisição para autenticação.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        return parent::handle($request, $next, ...$guards);
    }
}
