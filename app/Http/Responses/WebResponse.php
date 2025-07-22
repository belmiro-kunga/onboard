<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;

class WebResponse
{
    public static function success(string $route, string $message = 'Operação realizada com sucesso!', array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with('success', $message);
    }

    public static function error(string $route, string $message = 'Ocorreu um erro!', array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with('error', $message);
    }

    public static function backSuccess(string $message = 'Operação realizada com sucesso!'): RedirectResponse
    {
        return back()->with('success', $message);
    }

    public static function backError(string $message = 'Ocorreu um erro!'): RedirectResponse
    {
        return back()->with('error', $message);
    }

    public static function backWithErrors($errors, string $message = 'Verifique os dados informados'): RedirectResponse
    {
        return back()->withErrors($errors)->with('error', $message)->withInput();
    }

    public static function redirectToLogin(string $message = 'Você precisa estar logado'): RedirectResponse
    {
        return redirect()->route('login')->with('error', $message);
    }

    public static function backWarning(string $message): RedirectResponse
    {
        return back()->with('warning', $message);
    }
}
