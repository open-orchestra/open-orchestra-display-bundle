<?php

namespace OpenOrchestra\DisplayBundle\Tests\EventSubscriber;

use Phake;
use OpenOrchestra\DisplayBundle\EventSubscriber\SendMessageSubscriber;
use OpenOrchestra\DisplayBundle\MailerEvents;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SendMessageSubscriberTest
 */
class SendMessageSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SendMessageSubscriber
     */
    protected $subscriber;

    protected $mailer;

    /**
     * set up the test
     */
    public function setUp()
    {
        $this->mailer = Phake::mock('Swift_Mailer');

        $this->subscriber = new SendMessageSubscriber($this->mailer);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->subscriber);
    }

    /**
     * Test event subscribed
     */
    public function testEventSubscribed()
    {
        $this->assertArrayHasKey(MailerEvents::SEND_MAIL, $this->subscriber->getSubscribedEvents());
        $this->assertArrayHasKey(KernelEvents::TERMINATE, $this->subscriber->getSubscribedEvents());
    }

    public function testSendMessage()
    {
        $message = Phake::mock('Swift_Message');

        $event = Phake::mock('OpenOrchestra\DisplayBundle\Event\MailerEvent');
        Phake::when($event)->getMessage()->thenReturn($message);

        $this->subscriber->addMessage($event);
        $this->subscriber->sendMessage();

        Phake::verify($this->mailer)->send($message);
    }
}
