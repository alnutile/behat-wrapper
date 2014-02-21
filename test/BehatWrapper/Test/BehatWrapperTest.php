<?php namespace BehatWrapper\Test;

use BehatWrapper\BehatCommand;
use BehatWrapper\BehatException;
use BehatWrapper\BehatWrapper;
use BehatWrapper\Test\Event\TestDispatcher;

class BehatWrapperTest extends BehatWrapperTestCase {

    public function testSetBehatBinary()
    {
        $binary_path = '/usr/bin/';
        $this->wrapper->setBehatBinary($binary_path);
        $this->assertEquals($binary_path . 'behat', $this->wrapper->getBehatBinary());
    }

    public function testSetDispatcher()
    {
        $dispatcher = new TestDispatcher();
        $this->wrapper->setDispatcher($dispatcher);
        $this->assertEquals($dispatcher, $this->wrapper->getDispatcher());
    }

    public function testBehatCommand()
    {
        $version = $this->wrapper->behat('--version');
        $version = trim($version);
        $this->assertEquals('Behat version DEV', $version);
    }

    /**
     * @expectedException \BehatWrapper\BehatException
     */
    public function testBehatCommandError($catchException = false)
    {
        $this->wrapper->behat('--what');
    }

    public function testBehatRun()
    {
        $testpath = $this->testfilepath;
        $command = BehatCommand::getInstance();
        $command->setOption('config', $this->behatyml);
        $command->setTestPath($testpath);
        $output = $this->wrapper->run($command);
        $this->assertContains('5 passed', $output);
    }

}
 