<?php

namespace BehatWrapper\Event;

/**
 * Interface implemented by output listeners.
 */
interface BehatPrepareListenerInterface
{

    public function handlePrepare(BehatEvent $event);
}
