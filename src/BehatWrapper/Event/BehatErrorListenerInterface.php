<?php

namespace BehatWrapper\Event;

/**
 * Interface implemented by output listeners.
 */
interface BehatErrorListenerInterface
{

    public function handleError($event);
}
