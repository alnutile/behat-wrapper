<?php namespace BehatWrapper;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

class BehatProcess extends Process
{
    /**
     * @var \BehatWrapper\BehatWrapper
     */
    protected $behat;

    /**
     * @var \BehatWrapper\BehatCommand
     */
    protected $command;

    /**
     * Constructs a BehatProcess object.
     *
     * @param \BehatWrapper\BehatWrapper $behat
     * @param \BehatWrapper\BehatCommand $command
     */
    public function __construct(BehatWrapper $behat, BehatCommand $command, $cwd = null)
    {
        $this->behat = $behat;
        $this->command = $command;

        // Build the command line options, flags, and arguments.
        $binary = ProcessUtils::escapeArgument($behat->getBehatBinary());
        $commandLine = rtrim($binary . ' ' . $command->getCommandLine());
        parent::__construct($commandLine, $cwd, null, null, $behat->getTimeout(), array());
    }

    /**
     * {@inheritdoc}
     */
    public function run($callback = null)
    {
        $event = new Event\BehatEvent($this->behat, $this, $this->command);
        $dispatcher = $this->behat->getDispatcher();

        try {
            $dispatcher->dispatch(Event\BehatEvents::BEHAT_PREPARE, $event);
            parent::run($callback);
            if ($this->isSuccessful()) {
                $dispatcher->dispatch(Event\BehatEvents::BEHAT_SUCCESS, $event);
            } else {
                $dispatcher->dispatch(Event\BehatEvents::BEHAT_ERROR, $event);
                throw new BehatException($this->getErrorOutput());
            }
        } catch (\RuntimeException $e) {
            $dispatcher->dispatch(Event\BehatEvents::BEHAT_ERROR, $event);
            throw new BehatException($e->getMessage());
        }
    }
}
