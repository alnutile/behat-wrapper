<?php namespace BehatWrapper\Test;

use BehatWrapper\BehatCommand;
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

    public function testBehatCommandError()
    {
        var_dump($this->runBadCommand());
    }

    public function runBadCommand($catchException = false)
    {
        try {
            $this->wrapper->behat('--what');
        } catch (BehatException $e) {
            var_dump("Is this being thrown 1");
            if (!$catchException) {
                throw $e;
            }
        }
    }
}
 