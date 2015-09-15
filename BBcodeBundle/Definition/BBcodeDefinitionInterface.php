<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface;

/**
 * Interface BBcodeDefinitionInterface
 */
interface BBcodeDefinitionInterface
{
    /**
     * Returns the tag name of this code definition
     *
     * @return string
     */
    public function getTagName();

    /**
     * Returns the replacement text of this code definition. This usually has little, if any meaning if the
     * CodeDefinition class was extended. For default, html replacement CodeDefinitions this returns the html
     * markup for the definition.
     *
     * @return string
     */
    public function getReplacementText();

    /**
     * Accepts a BBcodeElementNodeInterface that is defined by this BBcodeDefinition and returns the HTML
     * markup of the element. This is a commonly overridden class for custom BBcodeDefinitions
     * so that the content can be directly manipulated.
     *
     * @param BBcodeElementNodeInterface $el
     *
     * @return string
     */
    public function getHtml(BBcodeElementNodeInterface $el);

    /**
     * Accepts a BBcodeElementNodeInterface that is defined by this BBcodeDefinition and returns the HTML
     * markup of the element, in a preview context. This is a commonly overridden class for custom
     * BBcodeDefinitions so that the content can be directly manipulated.
     *
     * @param BBcodeElementNodeInterface $el
     *
     * @return string
     */
    public function getPreviewHtml(BBcodeElementNodeInterface $el);
}
