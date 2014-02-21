<?php namespace BehatWrapper;


use Symfony\Component\Process\Process;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BehatWrapper
{

    const BIN_PATH = "../../bin";

    /**
     * Path to the Behat binary.
     *
     * @var string
     */
    protected $behat_binary;

    public function __construct($behat_binary = null)
    {
        if ( null == $behat_binary ) {
            $behat_binary = self::BIN_PATH . '/behat';
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
    public function setGitBinary($behat_binary)
    {
        $this->behat_binary = $behat_binary;
        return $this;
    }

    public function getBehatBinary()
    {
        return $this->behat_binary;
    }

    public function run(BehatCommand $command)
    {
        $wrapper = $this;
        $process = new BehatProcess($this, $command);
        $process->run(function ($type, $buffer) use ($wrapper, $process, $command) {
            $event = new Event\BehatOutputEvent($wrapper, $process, $command, $type, $buffer);
            $wrapper->getDispatcher()->dispatch(Event\BehatEvents::GIT_OUTPUT, $event);
        });
    }

}