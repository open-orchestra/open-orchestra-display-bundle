<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use FOS\HttpCache\Handler\TagHandler;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\ModelInterface\Model\CacheableInterface;

/**
 * Class CacheableManager
 */
class CacheableManager
{
    /**
     * @var TagHandler
     */
    protected $tagHandler;

    /**
     * @param TagHandler   $tagHandler
     */
    public function __construct(TagHandler $tagHandler)
    {
        $this->tagHandler = $tagHandler;
    }

    /**
     * Set response cache headers
     * 
     * @param Response $response
     * @param int      $maxAge
     * @param string   $status
     * @param bool     $hasEsi
     *
     * @return Response $response
     */
    public function setResponseCacheParameters(Response $response, $maxAge, $status = CacheableInterface::CACHE_PRIVATE, $hasEsi = false)
    {
        $this->setResponseStatus($response, $status);
        $this->setResponseMaxAge($response, $status, $maxAge, $hasEsi);

        return $response;
    }

    /**
     * Set response status
     * 
     * @param Response $response
     * @param string   $status
     */
    protected function setResponseStatus(Response $response, $status)
    {
        if (CacheableInterface::CACHE_PUBLIC == $status) {
            $response->setPublic();
        } else {
            $response->setPrivate();
        }
    }

    /**
     * Set response max age
     *
     * @param Response $response
     * @param string   $status
     * @param int      $maxAge
     * @param bool     $hasEsi
     */
    protected function setResponseMaxAge(Response $response, $status, $maxAge, $hasEsi)
    {
        if (-1 === $maxAge) {
            $maxAge = 2629743;
        }
        if (true === $hasEsi && CacheableInterface::CACHE_PUBLIC == $status) {
            $response->setSharedMaxAge($maxAge);
        } else {
            $response->setMaxAge($maxAge);
        }
    }

    /**
     * Add tags to include in the response
     * 
     * @param array $tags
     */
    public function addCacheTags(array $tags)
    {
        $this->tagHandler->addTags($tags);
    }

    /**
     * Invalidate cache by tags
     * 
     * @param array $tags
     */
    public function invalidateTags(array $tags)
    {
        if (!is_null($this->tagHandler)) {
            $this->tagHandler->invalidateTags($tags);
        }
    }
}
