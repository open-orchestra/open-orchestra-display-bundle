<?php

namespace OpenOrchestra\DisplayBundle\Manager;

/**
 * Class TagManager
 */
class TagManager
{
    public function formatContentTypeTag($contentType)
    {
        return 'contentType-' . $contentType;
    }

    public function formatContentIdTag($contentId)
    {
        return 'contentId-' . $contentId;
    }

    public function formatNodeIdTag($nodeId)
    {
        return 'nodeId-' . $nodeId;
    }

    public function formatMediaId($mediaId)
    {
        return 'mediaId-' . $mediaId;
    }
}
