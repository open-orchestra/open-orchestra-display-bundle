<?php

namespace OpenOrchestra\DisplayBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class NodeNotFoundException
 */
class NodeNotFoundException extends HttpException
{
    /**
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct(404, 'Node Not Found : ' . $message);
    }
}
