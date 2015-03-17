<?php

namespace OpenOrchestra\DisplayBundle\Manager;

/**
 * Class TagManager
 */
class TagManager
{
    public function formatNodeIdTag($nodeId)
    {
        return 'node-' . $nodeId;
    }

    public function formatLanguageTag($language)
    {
        return 'language-' . $language;
    }

    public function formatSiteIdTag($siteId)
    {
        return 'site-' . $siteId;
    }

    public function formatBlockTypeTag($blockType)
    {
        return 'block-' . $blockType;
    }

    public function formatContentTypeTag($contentType)
    {
        return 'contentType-' . $contentType;
    }

    public function formatContentIdTag($contentId)
    {
        return 'contentId-' . $contentId;
    }

    public function formatMediaId($mediaId)
    {
        return 'mediaId-' . $mediaId;
    }
}
