<?php

namespace BehatWrapper\Test\Event;

use BehatWrapper\Event\BehatEvent;

class TestListener
{
    /**
     * The methods that were called.
     *
     * @var array
     */
    protected $methods = array();

    /**
     * The event object passed to the onPrepare method.
     *
     * @var \GitWrapper\Event\GitEvent
     */
    protected $event;

    public function methodCalled($method)
    {
        return in_array($method, $this->methods);
    }

    /**
     * @return \BehatWrapper\Event\BehatWrapperEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    public function onPrepare(BehatEvent $event)
    {
        $this->methods[] = 'onPrepare';
        $this->event = $event;
    }

    public function onSuccess(BehatEvent $event)
    {
        $this->methods[] = 'onSuccess';
    }

    public function onError(BehatEvent $event)
    {
        $this->methods[] = 'onError';
    }

}
