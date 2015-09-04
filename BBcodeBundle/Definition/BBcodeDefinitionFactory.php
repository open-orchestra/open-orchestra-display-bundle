<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition;
use JBBCode\InputValidator;

/**
 * Class BBcodeDefinitionFactory
 */
class BBcodeDefinitionFactory
{
    protected $className;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * Create a new definition
     * 
     * @param string $tag
     * @param string $html
     * boolean        $useOption
     * boolean        $parseContent
     * integer        $nestLimit
     * InputValidator $optionValidator
     * InputValidator $bodyValidator
     */
    public function create(
        $tag, $html, $useOption = false, $parseContent = true,
        $nestLimit = -1, InputValidator $optionValidator = null, InputValidator $bodyValidator = null
    ) {
        return new $this->className($tag, $html, $useOption, $parseContent, $nestLimit, $optionValidator, $bodyValidator);
    }
}
