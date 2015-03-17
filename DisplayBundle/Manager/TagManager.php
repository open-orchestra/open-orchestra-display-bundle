<?php

namespace OpenOrchestra\DisplayBundle\Manager;

/**
 * Class TagManager
 */
class TagManager
{
    /**
     * Format node id tag
     * 
     * @param string $nodeId
     * 
     * return string
     */
    public function formatNodeIdTag($nodeId)
    {
        return 'node-' . $nodeId;
    }

    /**
     * Format language tag
     * 
     * @param string $language
     * 
     * return string
     */
    public function formatLanguageTag($language)
    {
        return 'language-' . $language;
    }

    /**
     * Format site id tag
     * 
     * @param string $siteId
     * 
     * return string
     */
    public function formatSiteIdTag($siteId)
    {
        return 'site-' . $siteId;
    }

    /**
     * Format block type tag
     * 
     * @param string $blockType
     * 
     * return string
     */
    public function formatBlockTypeTag($blockType)
    {
        return 'block-' . $blockType;
    }

    /**
     * Format content type tag
     * 
     * @param string $contentType
     * 
     * return string
     */
    public function formatContentTypeTag($contentType)
    {
        return 'contentType-' . $contentType;
    }

    /**
     * Format content id tag
     * 
     * @param string $contentId
     * 
     * return string
     */
    public function formatContentIdTag($contentId)
    {
        return 'contentId-' . $contentId;
    }

    /**
     * Format media id tag
     * 
     * @param string $mediaId
     * 
     * return string
     */
    public function formatMediaId($mediaId)
    {
        return 'mediaId-' . $mediaId;
    }
}
