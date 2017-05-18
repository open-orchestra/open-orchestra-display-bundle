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
     * @deprecated use OpenOrchestra\DisplayBundle\Manager\ContextManager::getCurrentSiteLanguage
     * @return string
     */
    public function getCurrentSiteDefaultLanguage()
    {
        return parent::getCurrentSiteLanguage();
    }
}
