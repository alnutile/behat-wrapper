<?php

namespace BehatWrapper\Event;

/**
 * Interface implemented by output listeners.
 */
interface BehatOutputListenerInterface
{

    public function handleOutput(BehatOutputEvent $event);
}
