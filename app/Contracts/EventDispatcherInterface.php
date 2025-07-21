<?php

namespace App\Contracts;

interface EventDispatcherInterface
{
    /**
     * Dispatch an event and call the listeners.
     *
     * @param object $event
     * @return object|null
     */
    public function dispatch(object $event);
}