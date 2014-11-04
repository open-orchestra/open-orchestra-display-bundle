<?php

namespace PHPOrchestra\DisplayBundle\Manager;

use PHPOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SiteManager
 */

class SiteManager implements CurrentSiteIdInterface
{
    /**
     * @var string
     */
    protected $siteId;
    protected $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $request = $this->requestStack->getCurrentRequest();

        if (!is_null($request)) {
            $this->siteId = $request->server->get('SYMFONY__SITE');
        } else {
            $this->siteId = 1;
        }
    }

    /**
     * @return string
     */
    public function getCurrentSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param string $siteId
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }
}
