<?php

namespace BehatWrapper\Event;

use BehatWrapper\BehatCommand;
use BehatWrapper\BehatWrapper;
use Symfony\Component\Process\Process;

/**
 * Event instance passed when output is returned from Behat commands.
 */
class BehatPrepareEvent extends BehatEvent
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $buffer;

    /**
     * Constructs a BehatEvent object.
     *
     * @param \BehatWrapper\BehatWrapper $wrapper
     *   The BehatWrapper object that likely instantiated this class.
     * @param \Symfony\Component\Process\Process $process
     *   The Process object being run.
     * @param \BehatWrapper\BehatCommand $command
     *   The BehatCommand object being executed.
     * @param string type
     * @param string buffer
     */
    public function __construct(BehatWrapper $wrapper, Process $process, BehatCommand $command)
    {
        parent::__construct($wrapper, $process, $command);
    }

    public function getOptions()
    {
        return "Options time";
    }
}
