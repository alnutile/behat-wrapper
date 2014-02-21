<?php

namespace BehatWrapper\Event;

use BehatWrapper\BehatCommand;
use BehatWrapper\BehatWrapper;
use Symfony\Component\Process\Process;

/**
 * Event instance passed when output is returned from Behat commands.
 */
class BehatOutputEvent extends BehatEvent
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
    public function __construct(BehatWrapper $wrapper, Process $process, BehatCommand $command, $type, $buffer)
    {
        parent::__construct($wrapper, $process, $command);
        $this->type = $type;
        $this->buffer = $buffer;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * Tests wheter the buffer was captured from STDERR.
     */
    public function isError()
    {
        return (Process::ERR == $this->type);
    }
}
