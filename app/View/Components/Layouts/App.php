<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class App extends Component
{
    public $title;
    public $description;
    public $showHeader;

    /**
     * Create a new component instance.
     */
    public function __construct($title = null, $description = null, $showHeader = true)
    {
        $this->title = $title;
        $this->description = $description;
        $this->showHeader = $showHeader;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.app');
    }
}
