<?php

namespace OpenOrchestra\BBcodeBundle\Parser;

use JBBCode\Parser;
use JBBCode\CodeDefinitionSet;
use JBBCode\InputValidator;

/**
 * Class BBcodeParser
 */
class BBcodeParser
{
    protected $parser;

    /**
     * @param Parser            $parser
     * @param CodeDefinitionSet $codeDefinitionSet
     */
    public function __construct(Parser $parser, CodeDefinitionSet $codeDefinitionSet)
    {
        $this->parser = $parser;
        $this->parser->addCodeDefinitionSet($codeDefinitionSet);
    }

    /**
     * Adds a simple (text-replacement only) bbcode definition
     *
     * @param string         $tagName           the tag name (for example the b in [b])
     * @param string         $replace           the html to use, with {param} and optionally {option} for replacements
     * @param boolean        $useOption         whether or not this bbcode uses the secondary {option} replacement
     * @param boolean        $parseContent      whether or not to parse the content within these elements
     * @param integer        $nestLimit         an optional limit of the number of elements of this kind that can be nested
     *                                          within each other before the parser stops parsing them.
     * @param InputValidator $optionValidator   the validator to run {option} through
     * @param BodyValidator  $bodyValidator     the validator to run {param} through (only used if $parseContent == false)
     */
    public function addBBcode(
        $tagName, $replace, $useOption = false, $parseContent = true, $nestLimit = -1,
        InputValidator $optionValidator = null, InputValidator $bodyValidator = null
    ){
        $this->parser = $this->parser->addBBCode($tagName, $replace, $useOption, $parseContent, $nestLimit, $optionValidator, $bodyValidator);
    }

    /**
     * Get html from BBcode
     * 
     * @param string $text
     * 
     * @return string
     */
    public function getAsHtml($text)
    {
        $this->parser->parse($text);

        return $this->parser->getAsHTML();
    }

    /**
     * Parse BBcode to fix unclosed tags
     * 
     * @param string $text
     * 
     * @return string
     */
    public function getAsBBcode($text)
    {
        $this->parser->parse($text);

        return $this->parser->getAsBBCode();
    }

    /**
     * Remove all BBcode to get raw text
     * 
     * @param string $text
     * 
     * @return string
     */
    public function getAsText($text)
    {
        $this->parser->parse($text);

        return $this->parser->getAsText();
    }
}
