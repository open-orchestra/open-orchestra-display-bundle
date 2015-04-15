<?php

namespace OpenOrchestra\DisplayBundle\EventSubscriber;

use OpenOrchestra\DisplayBundle\MailerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SendMessageSubscriber
 */
class SendMessageSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            MailerEvents::SEND_MAIL => 'addMessage',
            KernelEvents::TERMINATE => 'sendMessages',
        );
    }
}