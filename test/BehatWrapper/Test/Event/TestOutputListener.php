<?php

namespace BehatWrapper\Test\Event;

use BehatWrapper\Event\BehatOutputEvent;
use BehatWrapper\Event\BehatOutputListenerInterface;

class TestOutputListener implements BehatOutputListenerInterface
{
    /**
     * @var \BehatWrapper\Event\BehatOutputEvent
     */
    protected $event;

    /**
     * @return BehatWrapper\Event\BehatOutputEvent
     */
    public function getLastEvent()
    {
        return $this->event;
    }

    public function handleOutput(BehatOutputEvent $event)
    {
        $this->event = $event;
    }
}
