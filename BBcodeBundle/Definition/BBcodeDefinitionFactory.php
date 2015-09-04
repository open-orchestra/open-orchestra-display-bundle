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
     * @param string  $tag
     * @param string  $html
     * boolean        $useOption
     * boolean        $parseContent
     * integer        $nestLimit
     * array          $optionValidator
     * InputValidator $bodyValidator
     */
    public function create(
        $tag, $html, $useOption = false, $parseContent = true,
        $nestLimit = -1, array $optionValidator = array(), InputValidator $bodyValidator = null
    ) {
        return call_user_func(
            array($this->className, 'construct'),
            $tag, $html, $useOption, $parseContent, $nestLimit, $optionValidator, $bodyValidator
        );
    }
}
