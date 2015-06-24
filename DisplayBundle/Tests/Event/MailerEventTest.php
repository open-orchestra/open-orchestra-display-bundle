<?php

namespace OpenOrchestra\DisplayBundle\Tests\Event;

use OpenOrchestra\DisplayBundle\Event\MailerEvent;
use Phake;

/**
 * Test MailerEventTest
 */
class MailerEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MailerEvent
     */
    protected $event;

    protected $message;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->message = Phake::mock('Swift_Message');

        $this->event = new MailerEvent($this->message);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event', $this->event);
    }

    /**
     * Test getMessage
     */
    public function testGetMessage()
    {
        $this->assertSame($this->message, $this->event->getMessage());
    }
}
