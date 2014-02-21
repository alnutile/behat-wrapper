<?php namespace BehatWrapper\Event;

/**
 * Static list of events thrown by this library.
 */
final class BehatEvents
{
    /**
     * Event thrown prior to executing a behat command.
     *
     * @var string
     */
    const BEHAT_PREPARE = 'behat.command.prepare';

    /**
     * Event thrown when real-time output is returned from the Behat command.
     *
     * @var string
     */
    const BEHAT_OUTPUT = 'behat.command.output';

    /**
     * Event thrown after executing a succesful behat command.
     *
     * @var string
     */
    const BEHAT_SUCCESS = 'behat.command.success';

    /**
     * Event thrown after executing a unsuccesful behat command.
     *
     * @var string
     */
    const BEHAT_ERROR = 'behat.command.error';

}
