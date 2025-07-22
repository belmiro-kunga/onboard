<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Exibe a página de analytics do usuário.
     */
    public function index(): View
    {
        // Lógica para buscar dados de analytics...
        return view('analytics.index');
    }
}