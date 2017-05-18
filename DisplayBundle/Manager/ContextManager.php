<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ContextManager
 */
class ContextManager implements ContextInterface
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
     * Get the current language of the current site
     *
     * @return string
     */
    public function getCurrentSiteLanguage()
    {
        if (is_null($this->currentLanguage) && ($request = $this->requestStack->getMasterRequest())) {
            $this->currentLanguage = $request->get('language', $request->getLocale());
        }

        return $this->currentLanguage;
    }

    /**
     * @param string $currentLanguage
     */
    public function setCurrentLanguage($currentLanguage)
    {
        $this->currentLanguage = $currentLanguage;
    }
}
