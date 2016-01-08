<?php

namespace OpenOrchestra\DisplayBundle\Tests\EventSubscriber;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Phake;
use OpenOrchestra\DisplayBundle\EventSubscriber\SendMessageSubscriber;
use OpenOrchestra\DisplayBundle\MailerEvents;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SendMessageSubscriberTest
 */
class SendMessageSubscriberTest extends AbstractBaseTestCase
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

    /**
     * Test send message
     */
    public function testSendMessage()
    {
        $message = Phake::mock('Swift_Message');

        $event = Phake::mock('OpenOrchestra\DisplayBundle\Event\MailerEvent');
        Phake::when($event)->getMessage()->thenReturn($message);

        $this->subscriber->addMessage($event);
        $this->subscriber->addMessage($event);

        $this->subscriber->sendMessages();
        Phake::verify($this->mailer, Phake::times(2))->send($message);
    }

}
