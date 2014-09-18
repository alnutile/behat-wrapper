<?php

namespace BehatWrapper\Event;

/**
 * Interface implemented by output listeners.
 */
interface BehatSuccessListenerInterface
{

    public function handleSuccess($event);
}
