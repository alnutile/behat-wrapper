<?php

namespace BehatWrapper\Test;

use BehatWrapper\Event\BehatEvents;
use BehatWrapper\BehatException;
use BehatWrapper\BehatWrapper;
use BehatWrapper\Test\Event\TestListener;
use Symfony\Component\Filesystem\Filesystem;

class BehatWrapperTestCase extends \PHPUnit_Framework_TestCase
{
    const WORKING_DIR = 'build/test/wc';

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var \BehatWrapper\BehatWrapper
     */
    protected $wrapper;

    public function setUp()
    {
        parent::setUp();
        $this->wrapper = new BehatWrapper();
    }

    /**
     * Adds the test listener for all events, returns the listener.
     *
     * @return \BehatWrapper\Test\Event\TestListener
     */
    public function addListener()
    {
        $dispatcher = $this->wrapper->getDispatcher();
        $listener = new TestListener();

        $dispatcher->addListener(BehatEvents::BEHAT_PREPARE, array($listener, 'onPrepare'));
        $dispatcher->addListener(BehatEvents::BEHAT_SUCCESS, array($listener, 'onSuccess'));
        $dispatcher->addListener(BehatEvents::BEHAT_ERROR, array($listener, 'onError'));

        return $listener;
    }


}