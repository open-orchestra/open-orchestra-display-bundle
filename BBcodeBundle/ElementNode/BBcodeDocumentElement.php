<?php

namespace OpenOrchestra\BBcodeBundle\ElementNode;

use JBBCode\DocumentElement;

/**
 * Class BBcodeElementNode
 */
class BBcodeDocumentElement extends DocumentElement
{
    /**
     * Iterates through the document's children to return a html version,
     * in a preview context
     *
     * @return string
     */
    public function getAsPreviewHTML()
    {
        $html = "";
        foreach ($this->getChildren() as $child) {
            $html .= $child->getAsPreviewHTML();
        }

        return $html;
    }
}