<?php

namespace OpenOrchestra\BBcodeBundle\ElementNode;

use JBBCode\TextNode;

/**
 * Class BBcodeTextNode
 */
class BBcodeTextNode extends TextNode
{
    /**
     * Returns the html representation of this node, in a preview context
     *
     * @return string
     */
    public function getAsPreviewHTML()
    {
        return $this->getAsHTML();
    }
}