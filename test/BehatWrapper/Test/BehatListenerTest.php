<?php

namespace BehatWrapper\Test;

use BehatWrapper\BehatCommand;
use BehatWrapper\Event\BehatEvent;
use Symfony\Component\Process\Process;

class BehatListenerTest extends BehatWrapperTestCase
{
    public function testListener()
    {
        $listener = $this->addListener();
        $this->wrapper->version();

        $this->assertTrue($listener->methodCalled('onPrepare'));
        $this->assertTrue($listener->methodCalled('onSuccess'));
        $this->assertFalse($listener->methodCalled('onError'));
    }

    public function testListenerError()
    {
        $listener = $this->addListener();
        $this->runBadCommand(true);

        $this->assertTrue($listener->methodCalled('onPrepare'));
        $this->assertFalse($listener->methodCalled('onSuccess'));
        $this->assertTrue($listener->methodCalled('onError'));
    }

    public function testEvent()
    {
        $process = new Process('');
        $command = BehatCommand::getInstance();
        $event = new BehatEvent($this->wrapper, $process, $command);

        $this->assertEquals($this->wrapper, $event->getWrapper());
        $this->assertEquals($process, $event->getProcess());
        $this->assertEquals($command, $event->getCommand());
    }


}
