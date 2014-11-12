<?php

namespace PHPOrchestra\DisplayBundle\Manager;

use PHPOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use PHPOrchestra\ModelBundle\Model\SiteInterface;
use PHPOrchestra\ModelBundle\Repository\SiteRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SiteManager
 */
class SiteManager implements CurrentSiteIdInterface
{
    protected $siteId;
    protected $requestStack;
    protected $siteRepository;

    /**
     * @param RequestStack   $requestStack
     * @param SiteRepository $siteRepository
     */
    public function __construct(RequestStack $requestStack, SiteRepository $siteRepository)
    {
        $this->requestStack = $requestStack;
        $this->siteRepository = $siteRepository;
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

    /**
     * Get the current default language of the current site
     *
     * @return string
     */
    public function getCurrentSiteDefaultLanguage()
    {
        /** @var SiteInterface $site */
        $site = $this->siteRepository->findBySiteId($this->getCurrentSiteId());

        return $site->getDefaultLanguage();
    }
}
