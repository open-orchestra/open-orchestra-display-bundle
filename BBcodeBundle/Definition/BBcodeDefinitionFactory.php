<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

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
     * @param string         $tag
     * @param string         $html
     * @param boolean        $useOption
     * @param boolean        $parseContent
     * @param integer        $nestLimit
     * @param array          $optionValidator
     * @param InputValidator $bodyValidator
     */
    public function create(
        $tag, $html, $useOption = false, $parseContent = true,
        $nestLimit = -1, array $optionValidator = array(), InputValidator $bodyValidator = null
    ){
        return new $this->className($tag, $html, $useOption, $parseContent, $nestLimit, $optionValidator, $bodyValidator);
    }
}
