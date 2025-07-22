<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class SimpleViewController extends BaseController
{
    protected string $viewName;
    protected array $defaultData = [];

    public function __construct(string $viewName, array $defaultData = [])
    {
        $this->viewName = $viewName;
        $this->defaultData = $defaultData;
    }

    /**
     * Display the main view
     */
    public function index()
    {
        $data = $this->defaultData;
        
        // Adicionar dados comuns se usuário autenticado
        if (auth()->check()) {
            $data["user"] = auth()->user();
            $data["notifications_count"] = auth()->user()->notifications()->unread()->count();
        }
        
        return $this->simpleView($this->viewName, $data);
    }

    /**
     * Create static instance for specific view
     */
    public static function for(string $viewName, array $data = []): self
    {
        return new static($viewName, $data);
    }
}
