<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\ModelInterface\Model\CacheableInterface;

/**
 * Class CacheableManager
 */
class CacheableManager
{
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
        $reponse = $this->setResponseStatus($response, $status);
        $response = $this->setResponseMaxAge($response, $maxAge);

        return $response;
    }

    /**
     * Set response as public if required
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

        return $response;
    }

    /**
     * Set response max age
     * 
     * @param Response $response
     * @param int      $maxAge
     *
     * @return Response
     */
    protected function setResponseMaxAge(Response $response, $maxAge)
    {
        if ($maxAge != 0) {
            if (-1 === $maxAge) {
                $maxAge = 2629743;
            }
            $response->setMaxAge($maxAge);
            $response->setSharedMaxAge($maxAge);
        }

        return $response;
    }
}
