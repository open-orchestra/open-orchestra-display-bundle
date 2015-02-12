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
    protected $siteId;
    protected $requestStack;
    protected $currentLanguage;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return string
     */
    public function getCurrentSiteId()
    {
        if (is_null($this->siteId)) {
            $this->siteId = $this->requestStack->getMasterRequest()->get('siteId');
        }

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
        if (is_null($this->currentLanguage) && ($this->requestStack->getMasterRequest())) {
            $this->currentLanguage = $this->requestStack->getMasterRequest()->getLocale();
        }

        return $this->currentLanguage;
    }
}
