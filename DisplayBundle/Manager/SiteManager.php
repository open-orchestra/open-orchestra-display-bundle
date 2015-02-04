<?php

namespace PHPOrchestra\DisplayBundle\Manager;

use PHPOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use PHPOrchestra\ModelInterface\Model\SiteInterface;
use PHPOrchestra\ModelInterface\Repository\SiteRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SiteManager
 */
class SiteManager implements CurrentSiteIdInterface
{
    protected $siteId = '0';
    protected $requestStack;
    protected $siteRepository;

    /**
     * @param RequestStack            $requestStack
     * @param SiteRepositoryInterface $siteRepository
     */
    public function __construct(RequestStack $requestStack, SiteRepositoryInterface $siteRepository)
    {
        $this->requestStack = $requestStack;
        $this->siteRepository = $siteRepository;
        $request = $this->requestStack->getCurrentRequest();

        if (!is_null($request)) {
            $siteId = $request->server->get('SYMFONY__SITE');
            $site = $this->siteRepository->findOneBySiteIdNotDeleted($siteId);
            if ($site) {
                $this->siteId = $request->server->get('SYMFONY__SITE');
            }
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
     * Return false if current site is off
     *
     * @return string|false
     */
    public function getCurrentSiteDefaultLanguage()
    {
        /** @var SiteInterface $site */
        $site = $this->siteRepository->findOneBySiteId($this->getCurrentSiteId());

        if ($site) {
            return $site->getDefaultLanguage();
        }

        return false;
    }
}
