<?php

namespace OpenOrchestra\DisplayBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Swift_Message;

/**
 * Class MailerEvent
 */
class MailerEvent extends Event
{
    protected $message;

    /**
     * @param Swift_Message $message
     */
    public function __construct(Swift_Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return Swift_Message
     */
    public function getMessage()
    {
        return $this->message;
    }

}
