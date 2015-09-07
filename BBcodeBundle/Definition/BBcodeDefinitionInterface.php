<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

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
}
