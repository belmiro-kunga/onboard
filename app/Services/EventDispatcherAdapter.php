<?php

namespace App\Services;

use App\Contracts\EventDispatcherInterface;
use Illuminate\Contracts\Events\Dispatcher;

class EventDispatcherAdapter implements EventDispatcherInterface
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * EventDispatcherAdapter constructor.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatch an event and call the listeners.
     *
     * @param object $event
     * @return object|null
     */
    public function dispatch(object $event)
    {
        return $this->dispatcher->dispatch($event);
    }
}