<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Exibe a página de boas-vindas.
     */
    public function index(): View
    {
        // Lógica para exibir página de boas-vindas...
        return view('welcome');
    }
}