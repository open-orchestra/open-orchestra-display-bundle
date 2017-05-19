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
    protected $language;

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
    public function getSiteId()
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
    public function getSiteLanguage()
    {
        if (is_null($this->language) && ($request = $this->requestStack->getMasterRequest())) {
            $this->language = $request->get('language', $request->getLocale());
        }

        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
}
