<?php

namespace OpenOrchestra\DisplayBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BlockEvent
 */
class BlockEvent extends Event
{
    protected $block;
    protected $response;

    /**
     * @param ReadBlockInterface $block
     * @param Response           $response
     */
    public function __construct(ReadBlockInterface $block, Response $response = null)
    {
        $this->block = $block;
        $this->response = $response;
    }

    /**
     * @return ReadBlockInterface
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
