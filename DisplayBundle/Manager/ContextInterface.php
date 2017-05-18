<?php

namespace OpenOrchestra\DisplayBundle\Manager;

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * @return string
     */
    public function getSiteId();

    /**
     * @param string $siteId
     */
    public function setSiteId($siteId);

    /**
     * Get the current language of the current site
     *
     * @return string
     */
    public function getSiteLanguage();

    /**
     * @param string $currentLanguage
     */
    public function setLanguage($currentLanguage);
}
