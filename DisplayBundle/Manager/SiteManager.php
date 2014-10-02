<?php

namespace PHPOrchestra\DisplayBundle\Manager;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SiteManager
 */
class SiteManager {
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
        $this->siteId = $request->server->get('SYMFONY__SITE');
    }

    /**
     * @return string
     */
    public function getSiteId()
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