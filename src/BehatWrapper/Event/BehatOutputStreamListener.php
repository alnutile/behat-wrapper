<?php

namespace BehatWrapper\Event;

/**
 * Event handler that streams real-time output from Behat commands to STDOUT and
 * STDERR.
 */
class BehatOutputStreamListener implements BehatOutputListenerInterface
{

    public function handleOutput(BehatOutputEvent $event)
    {
        $handler = $event->isError() ? STDERR : STDOUT;
        fputs($handler, $event->getBuffer());
    }
}
