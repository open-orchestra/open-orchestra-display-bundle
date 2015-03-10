<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class CacheableManager
 */
class CacheableManager
{
    /**
     * @param int      $maxAge
     * @param Response $response
     *
     * @return Response
     */
    public function setMaxAge($maxAge, Response $response)
    {
        if ($maxAge != 0) {
            if (-1 === $maxAge) {
                $maxAge = 2629743;
            }
            $response->setPublic();
            $response->setMaxAge($maxAge);
        }

        return $response;
    }
}
