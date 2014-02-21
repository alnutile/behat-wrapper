<?php namespace BehatWrapper\Test;

use BehatWrapper\BehatCommand;


class BehatCommandTest extends \PHPUnit_Framework_TestCase {

    protected $path;

    public function setUp()
    {
        $path = dirname( __FILE__ );
        $path = explode("/", $path);
        $path = array_slice($path, 0, -2);
        $path = implode("/", $path);
        $path = $path . '/features/test.feature';
        $this->path = $path;
    }

    public function testCommand()
    {
        $behat = BehatCommand::getInstance()
            ->setOption('format', 'pretty')
            ->setFlag('version')
            ->setTestPath($this->path);

        $expected = "--format='pretty' --version $this->path";
        $commandLine = $behat->getCommandLine();
        $this->assertEquals($expected, $commandLine);
    }


}
 