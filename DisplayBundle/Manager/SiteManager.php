<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;

/**
 * Class SiteManager
 *
 * @deprecated use OpenOrchestra\DisplayBundle\Manager\ContextManager
 */
class SiteManager extends ContextManager implements CurrentSiteIdInterface
{
    /**
     * Get the current language of the current site
     *
     * @deprecated use OpenOrchestra\DisplayBundle\Manager\ContextManager::getSiteLanguage
     * @return string
     */
    public function getCurrentSiteDefaultLanguage()
    {
        return parent::getSiteLanguage();
    }

    /**
     * Get the current language of the current site
     *
     * @deprecated use OpenOrchestra\DisplayBundle\Manager\ContextManager::getSiteId
     * @return string
     */
    public function getCurrentSiteId()
    {
        return parent::getSiteId();
    }

    /**
     * @param string $currentLanguage
     * @deprecated use OpenOrchestra\DisplayBundle\Manager\ContextManager::setLanguage
     */
    public function setCurrentLanguage($currentLanguage)
    {
        parent::setLanguage($currentLanguage);
    }
}
