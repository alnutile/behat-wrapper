<?php namespace BehatWrapper;

use Symfony\Component\Process\ProcessUtils;

class BehatCommand {

    /**
     * This is the path to the test file or test folder
     *
     * @var string
     */
    protected $testpath = '';

    protected $command = '';

    protected $options = array();

    /**
     * Path to behat minus the file name behat
     *
     * @var string|null
     */
    protected $directory;
    /**
     * Command line arguments passed to the Behat command.
     *
     * @var array
     */
    protected $args = array();

    /**
     * Constructs a BehatCommand object.
     *
     * Use BehatCommand::getInstance() as the factory method for this class.
     *
     * @param array $args
     *   The arguments passed to BehatCommand::getInstance().
     *
     */
    protected function __construct($args)
    {
        if ($args) {
            $this->command = array_shift($args);

            $options = end($args);
            if (is_array($options)) {
                $this->setOptions($options);
                array_pop($args);
            }

            foreach ($args as $arg) {
                $this->addArgument($arg);
            }
        }
    }

    public static function getInstance()
    {
        $args = func_get_args();
        return new static($args);
    }

    /**
     * Builds the args for behat eg --config or --tags etc
     *
     * @param string $arg
     *   The argument, e.g. --config, --tags, --profile.
     *
     * @return \BehatWrapper\BehatCommand
     */
    public function addArgument($arg)
    {
        $this->args[] = $arg;
        return $this;
    }

    /**
     * Sets a command line option.
     *
     * Option names are passed as-is to the command line, whereas the values are
     * escaped using \Symfony\Component\Process\ProcessUtils.
     *
     * @param string $option
     *   The option name, e.g. "--profile", "--tag".
     * @param string|true $value
     *   The option's value, pass true if the options is a flag.
     *
     * @return \BehatWrapper\BehatCommand
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
    }

    public function unsetOption($option)
    {
        unset($this->options[$option]);
        return $this;
    }

    /**
     * Sets multiple command line options.
     *
     * @param array $options
     *   An associative array of command line options.
     *
     * @return \BehatWrapper\BehatCommand
     */
    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }
        return $this;
    }



    /**
     * Returns Behat command being run
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Renders the arguments and options for the Behat command.
     *
     * @return string
     *
     * @see BehatComment::getCommand()
     * @see BehatComment::buildOptions()
     */
    public function getCommandLine()
    {
        $command = array(
            $this->getCommand(),
            $this->buildOptions(),
            $this->getTestPath(),
            join(' ', array_map(array('\Symfony\Component\Process\ProcessUtils', 'escapeArgument'), $this->args)),
        );
        return join(' ', array_filter($command));
    }

    /**
     * Sets a command line flag.
     *
     * @see \BehatWrapper\BehatCommand::setOption()
     *
     * @param string $option
     *   The flag name, e.g. "q", "a".
     *
     * @return \BehatWrapper\BehatCommand
     */
    public function setFlag($option)
    {
        return $this->setOption($option, true);
    }

    public function unsetFlag($option)
    {
        return $this->unsetOption($option);
    }

    /**
     * Builds the command line options for use in the Behat command.
     *
     * @return string
     */
    public function buildOptions()
    {
        $options = array();
        foreach ($this->options as $option => $values) {
            foreach ((array) $values as $value) {
                $prefix = (strlen($option) != 1) ? '--' : '-';
                $rendered = $prefix . $option;
                if ($value !== true) {
                    $rendered .= ('--' == $prefix) ? '=' : ' ';
                    $rendered .= ProcessUtils::escapeArgument($value);
                }
                $options[] = $rendered;
            }
        }
        return join(' ', $options);
    }

    /**
     * Set the test path
     *
     * @param string $testpath
     *   The path to the file or folder with tests
     *
     * @return \BehatWrapper\BehatCommand
     */
    public function setTestPath($testpath)
    {
        $this->testpath = $testpath;
        return $this;
    }

    public function getTestPath()
    {
        return $this->testpath;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

}