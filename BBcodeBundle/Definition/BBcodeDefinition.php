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

    /**
     * Get the html representation of the node, in a preview context
     * 
     * @param BBcodeElementNode $el
     * 
     * @return string
     */
    public function getPreviewHtml(BBcodeElementNodeInterface $el)
    {
        if (!$this->hasValidInputs($el)) {
            return $el->getAsBBCode();
        }

        $html = $this->getReplacementText();

        if ($this->usesOption()) {
            $options = $el->getAttribute();
            if (count($options)==1) {
                $vals = array_values($options);
                $html = str_ireplace('{option}', reset($vals), $html);
            } else{
                foreach ($options as $key => $val) {
                    $html = str_ireplace('{' . $key . '}', $val, $html);
                }
            }
        }

        $content = $this->getPreviewContent($el);

        $html = str_ireplace('{param}', $content, $html);

        return $html;
    }

    /**
     * @param BBcodeElementNodeInterface $el
     * 
     * @return string
     */
    protected function getPreviewContent(BBcodeElementNodeInterface $el){
        if ($this->parseContent()) {
            $content = "";
            foreach ($el->getChildren() as $child)
                $content .= $child->getAsPreviewHTML();
        } else {
            $content = "";
            foreach ($el->getChildren() as $child)
                $content .= $child->getAsBBCode();
        }
        return $content;
    }
    
}
