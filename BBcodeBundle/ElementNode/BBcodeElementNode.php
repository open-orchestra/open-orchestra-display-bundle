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
     * @return string
     */
    public function getAsHTML()
    {
        return $this->generateHtml();
    }

    /**
     * Return the element as html with all replacements made, in a preview context
     *
     * @return string
     */
    public function getAsPreviewHTML()
    {
        return $this->generateHtml(true);
    }

    /**
     * Return the element as html with all replacements made
     * in a preview context or not, depending on $preview
     * 
     * @param bool $preview
     * 
     * @return string
     */
    protected function generateHtml($preview = false)
    {
        if ($this->codeDefinition) {
            if ($preview) {

                return $this->codeDefinition->getPreviewHtml($this);
            } else {

                return $this->codeDefinition->getHtml($this);
            }
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
