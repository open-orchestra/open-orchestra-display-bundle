<?php 

namespace OpenOrchestra\BBcodeBundle\ElementNode;

use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionInterface;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface;
use JBBCode\ElementNode;

/**
 * Class BBcodeElementNode
 */
class BBcodeElementNode extends ElementNode implements BBcodeElementNodeInterface
{
    /**
     * Return the element as html with all replacements made
     *
     * @return the html representation of this node
     */
    public function getAsHTML()
    {
        if ($this->codeDefinition) {

            return $this->codeDefinition->getHtml($this);
        } else {

            return "";
        }
    }

    /**
     * Sets the CodeDefinition that defines this element.
     *
     * @param codeDef the code definition that defines this element node
     */
    public function setBBCodeDefinition(BBcodeDefinitionInterface $codeDef)
    {
        $this->codeDefinition = $codeDef;
        $this->setTagName($codeDef->getTagName());
    }
}
