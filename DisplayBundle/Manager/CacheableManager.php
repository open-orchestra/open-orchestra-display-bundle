<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use FOS\HttpCache\Handler\TagHandler;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\ModelInterface\Model\CacheableInterface;
use FOS\HttpCacheBundle\CacheManager;

/**
 * Class CacheableManager
 */
class CacheableManager
{
    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var TagHandler
     */
    protected $tagHandler;

    /**
     * @param CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
        $this->tagHandler = new TagHandler($cacheManager);
    }

    /**
     * Set response cache headers
     * 
     * @param Response $response
     * @param int      $maxAge
     * @param string   $status
     * 
     * @return Response $response
     */
    public function setResponseCacheParameters(Response $response, $maxAge, $status = CacheableInterface::CACHE_PRIVATE)
    {
        $this->setResponseStatus($response, $status);
        $this->setResponseMaxAge($response, $maxAge, $status);

        return $response;
    }

    /**
     * Set response status
     * 
     * @param Response $response
     * @param string   $status
     * 
     * @return Response $response
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
     * @param int      $maxAge
     *
     * @return Response
     */
    protected function setResponseMaxAge(Response $response, $maxAge, $status)
    {
        if ($maxAge != 0) {
            if (-1 === $maxAge) {
                $maxAge = 2629743;
            }
            $response->setMaxAge($maxAge);
            if (CacheableInterface::CACHE_PUBLIC == $status) {
                $response->setSharedMaxAge($maxAge);
            }
        }
    }

    /**
     * Tag response
     * 
     * @param Response $response
     * @param array    $tags
     */
    public function tagResponse(Response $response, $tags)
    {
        $this->cacheManager->tagResponse($response, $tags);
    }

    /**
     * Invalidate cache by tags
     * 
     * @param array $tags
     */
    public function invalidateTags(array $tags)
    {
        $this->tagHandler->invalidateTags($tags);
    }
}
