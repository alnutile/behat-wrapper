<?php namespace BehatWrapper;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BehatWrapper
{

    /**
     * Symfony event dispatcher object used by this library to dispatch events.
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Environment variables defined in the scope of the Git command.
     *
     * @var array
     */
    protected $env = array();

    /**
     * The timeout of the Behat command in seconds, defaults to 60.
     *
     * @var int
     */
    protected $timeout = 60;


    /**
     * An array of options passed to the proc_open() function.
     *
     * @var array
     */
    protected $procOptions = array();

    /**
     * @var \BehatWrapper\Event\BehatOutputListenerInterface
     */
    protected $streamListener;

    /**
     * @TODO better way to set this?
     */
    const BIN_PATH = "../../bin";

    protected $path;

    /**
     * Path to the Behat binary.
     *
     * @var string
     */
    protected $behat_binary;

    public function __construct($behat_binary = null)
    {
        if ( null == $behat_binary ) {
            $behat_binary = self::createBinaryPath();
        } else {
            $behat_binary = $behat_binary;
        }

        $this->setBehatBinary($behat_binary);
    }

    /**
     * Sets the path to the Behat binary.
     *
     * @param string $behat_binary
     *   Path to the Behat binary.
     *
     * @return \BehatWrapper\BehatWrapper
     */
    public function setBehatBinary($behat_binary)
    {
        $behat_binary = (substr($behat_binary, -1) != '/') ? $behat_binary . '/' : $behat_binary;
        $this->behat_binary = $behat_binary . 'behat';
        return $this;
    }

    public function getBehatBinary()
    {
        return $this->behat_binary;
    }

    public function run(BehatCommand $command, $cwd = null)
    {
        $wrapper = $this;
        $process = new BehatProcess($this, $command, $cwd);
        $process->run(function ($type, $buffer) use ($wrapper, $process, $command) {
            $event = new Event\BehatOutputEvent($wrapper, $process, $command, $type, $buffer);
            $wrapper->getDispatcher()->dispatch(Event\BehatEvents::BEHAT_OUTPUT, $event);
        });
        return $this->getOutput($process);
    }

    public function start(BehatCommand $command, $cwd = null)
    {
        $wrapper = $this;
        $process = new BehatProcess($this, $command, $cwd);
        $process->start(function ($type, $buffer) use ($wrapper, $process, $command) {
            $event = new Event\BehatOutputEvent($wrapper, $process, $command, $type, $buffer);
            $wrapper->getDispatcher()->dispatch(Event\BehatEvents::BEHAT_OUTPUT, $event);
        });
        return $process;
    }

    public function getOutput($process)
    {
        return $process->getOutput();
    }


    /**
     * Mostly for testing
     */
    public function version()
    {
        return $this->behat('--version');
    }


    /**
     * Gets the dispatcher used by this library to dispatch events.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     *
     * @todo move this out in __contruct so it can be more easily replaced or tested
     */
    public function getDispatcher()
    {
        if (!isset($this->dispatcher)) {
            $this->dispatcher = new EventDispatcher();
        }
        return $this->dispatcher;
    }

    /**
     * Sets the dispatcher used by this library to dispatch events.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     *   The Symfony event dispatcher object.
     *
     * @return \BehatWrapper\BehatWrapper
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    /**
     * Runs an arbitrary Behat command.
     *
     * The command is simply a raw command line entry for everything after the
     * Behat binary. For example, a `behat --version` command would be passed as
     * `version` via the first argument of this method.
     *
     * Note that no events are thrown by this method.
     *
     * @param string $commandLine
     *   The raw command containing the Behat options and arguments. The Behat
     *   binary should not be in the command, for example `behat --version` would
     *   translate to "--version".
     *
     * @param string $cwd
     *   The working path to the behat bin
     *
     * @return string
     *   The STDOUT returned by the Behat command.
     *
     * @throws \BehatWrapper\BehatException
     *
     * @see BehatWrapper::run()
     */
    public function behat($commandLine, $cwd = null)
    {
        $command = BehatCommand::getInstance($commandLine);

        $command->setDirectory($cwd);
        return $this->run($command);
    }

    /**
     * Gets the timeout of the Git command.
     *
     * @return int
     *   The timeout in seconds.
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Sets the options passed to proc_open() when executing the Behat command.
     *
     * @param array $options
     *   The options passed to proc_open().
     *
     * @return \BehatWrapper\BehatWrapper
     */
    public function setProcOptions(array $options)
    {
        $this->procOptions = $options;
        return $this;
    }

    /**
     * Gets the options passed to proc_open() when executing the Git command.
     *
     * @return array
     */
    public function getProcOptions()
    {
        return $this->procOptions;
    }

    public static function createBinaryPath()
    {
        $path = dirname( __FILE__ );
        $path = explode("/", $path);
        $path = array_slice($path, 0, -2);
        $path = implode("/", $path);
        $path = $path . '/bin/';
        return $path;
    }

    /**
     * Set whether or not to stream real-time output to STDOUT and STDERR.
     *
     * @param boolean $streamOutput
     *
     * @return \BehatWrapper\BehatWrapper
     */
    public function streamOutput($streamOutput = true)
    {
        if ($streamOutput && !isset($this->streamListener)) {
            $this->streamListener = new Event\BehatOutputStreamListener();
            $this->addOutputListener($this->streamListener);
        }

        if (!$streamOutput && isset($this->streamListener)) {
            $this->removeOutputListener($this->streamListener);
            unset($this->streamListener);
        }

        return $this;
    }

    /**
     * Adds output listener.
     *
     * @param \BehatWrapper\Event\BehatOutputListenerInterface $listener
     *
     * @return \BehatWrapper\BehatWrapper
     */
    public function addOutputListener(Event\BehatOutputListenerInterface $listener)
    {
        $this
            ->getDispatcher()
            ->addListener(Event\BehatEvents::BEHAT_OUTPUT, array($listener, 'handleOutput'))
        ;
        return $this;
    }

    /**
     * Removes an output listener.
     *
     * @param \BehatWrapper\Event\BehatOutputListenerInterface $listener
     *
     * @return \BehatWrapper\BehatWrapper
     */
    public function removeOutputListener(Event\BehatOutputListenerInterface $listener)
    {
        $this
            ->getDispatcher()
            ->removeListener(Event\BehatEvents::BEHAT_OUTPUT, array($listener, 'handleOutput'))
        ;
        return $this;
    }
}
