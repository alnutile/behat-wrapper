<?php namespace BehatWrapper\Event;

use BehatWrapper\BehatCommand;
use BehatWrapper\BehatWrapper;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Process\Process;


class BehatEvent extends Event
{
    /**
     * The BehatWrapper object that likely instantiated this class.
     *
     * @var \BehatWrapper\BehatWrapper
     */
    protected $wrapper;

    /**
     * The Process object being run.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * The BehatCommand object being executed.
     *
     * @var \BehatWrapper\BehatCommand
     */
    protected $command;

    /**
     * Constructs a BehatEvent object.
     *
     * @param \BehatWrapper\BehatWrapper $wrapper
     *   The BehatWrapper object that likely instantiated this class.
     * @param \Symfony\Component\Process\Process $process
     *   The Process object being run.
     * @param \BehatWrapper\BehatCommand $command
     *   The BehatCommand object being executed.
     */
    public function __construct(BehatWrapper $wrapper, Process $process, BehatCommand $command)
    {
        $this->wrapper = $wrapper;
        $this->process = $process;
        $this->command = $command;
    }

    /**
     * Gets the BehatWrapper object that likely instantiated this class.
     *
     * @return \BehatWrapper\BehatWrapper
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * Gets the Process object being run.
     *
     * @return \Symfony\Component\Process\Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * Gets the BehatCommand object being executed.
     *
     * @return \BehatWrapper\BehatCommand
     */
    public function getCommand()
    {
        return $this->command;
    }
}
