<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * Middleware para encriptar cookies automaticamente.
 */
class EncryptCookies extends Middleware
{
    /**
     * Manipula a requisição para encriptar cookies.
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
