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

    protected $cwd;

    /**
     * Constructs a BehatProcess object.
     *
     * @param \BehatWrapper\BehatWrapper $behat
     * @param \BehatWrapper\BehatCommand $command
     * @param working directory
     */
    public function __construct(BehatWrapper $behat, BehatCommand $command, $cwd = null)
    {
        $this->behat = $behat;
        $this->command = $command;
        $this->cwd = $cwd;
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
            $this->prepare($this->behat, $this->command, $this->cwd);
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

    /**
     * {@inheritdoc}
     */
    public function start($callback = null)
    {
        $this->prepare($this->behat, $this->command, $this->cwd);
        parent::start($callback);
    }

    public function prepare(BehatWrapper $behat, BehatCommand $command, $cwd = null)
    {
        // Build the command line options, flags, and arguments.
        $binary = ProcessUtils::escapeArgument($behat->getBehatBinary());
        $commandLine = rtrim($binary . ' ' . $command->getCommandLine());
        parent::__construct($commandLine, $cwd, null, null, $behat->getTimeout(), array());
    }
}
