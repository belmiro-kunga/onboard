<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationBell extends Component
{
    public $unreadCount;

    /**
     * Create a new component instance.
     */
    public function __construct($unreadCount = 0)
    {
        $this->unreadCount = $unreadCount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notification-bell');
    }
}
