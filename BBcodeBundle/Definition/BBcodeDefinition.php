<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

use JBBCode\CodeDefinition;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionInterface;
use JBBCode\InputValidator;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface;

/**
 * Class BBcodeDefinition
 */
class BBcodeDefinition extends CodeDefinition implements BBcodeDefinitionInterface
{
    /**
     * @param string         $tagName
     * @param string         $replacementText
     * @param boolean        $useOption
     * @param boolean        $parseContent
     * @param int            $nestLimit
     * @param array          $optionValidator
     * @param InputValidator $bodyValidator
     */
    public function __construct(
        $tagName, $replacementText, $useOption = false, $parseContent = true,
        $nestLimit = -1, $optionValidator = array(), InputValidator $bodyValidator = null
    ){
        $this->elCounter = 0;
        $this->setTagName($tagName);
        $this->setReplacementText($replacementText);
        $this->useOption = $useOption;
        $this->parseContent = $parseContent;
        $this->nestLimit = $nestLimit;
        $this->optionValidator = $optionValidator;
        $this->bodyValidator = $bodyValidator;
     }

    /**
     * Get the html representation of the node
     * 
     * @param BBcodeElementNode $el
     * 
     * @return string
     */
    public function getHtml(BBcodeElementNodeInterface $el)
    {
        return $this->asHtml($el);
    }
}
