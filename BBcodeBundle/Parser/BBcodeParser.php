<?php

namespace OpenOrchestra\BBcodeBundle\Parser;

use JBBCode\Parser;
use JBBCode\CodeDefinitionSet;
use JBBCode\InputValidator;
use JBBCode\CodeDefinitionBuilder;
use OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorCollectionInterface;
use OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorInterface;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionCollectionInterface;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionInterface;

/**
 * Class BBcodeParser
 */
class BBcodeParser
{
    protected $parser;
    protected $validators = array();

    /**
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Add/Override validators described in container configuration
     * 
     * @param array $validators
     */
    public function loadValidatorsFromConfiguration($validators) {
        foreach ($validators as $key => $class) {
            if (class_exists($class)) {
                $this->validators[$key] = new $class();
            }
        }
    }

    /**
     * Add/Override validators described in a tagged BBcodeValidatorCollectionInterface
     * 
     * @param BBcodeValidatorCollectionInterface $validator
     */
    public function loadValidatorsFromService(BBcodeValidatorCollectionInterface $collection)
    {
        foreach ($collection as $validator) {
            if ($validator instanceof BBcodeValidatorInterface) {
                $this->validators[$validator->getName()] = $validator;
            }
        }
    }

    /**
     * Add/Override tag definitions described in container configuration
     * 
     * @param array $codeDefinitions
     */
    public function loadDefinitionsFromConfiguration($codeDefinitions)
    {
        foreach ($codeDefinitions as $definition) {
            if (isset($definition['tag']) && isset($definition['html'])) {
                $parameters = array();
                if (isset($definition['parameters'])) {
                    $parameters = $definition['parameters'];
                }
                $this->addDefinition($definition['tag'], $definition['html'], $parameters);
            }
        }
    }

    /**
     * Add/Override definitions described in a BBcodeDefinitionCollectionInterface
     * 
     * @param BBcodeDefinitionCollectionInterface $collection
     */
    public function loadDefinitionsFromService(BBcodeDefinitionCollectionInterface $collection)
    {
        foreach ($collection as $definition) {
            if ($definition instanceof BBcodeDefinitionInterface) {
                $this->addDefinition($definition->getTag(), $defintion->getHtml(), $definition->getParameters());
            }
        }
    }

    /**
     * Add a definition to the parser. 
     * $parameters definitions is an array containing:
     * 
     * boolean        $useOption         whether or not this bbcode uses the secondary {option} replacement
     * boolean        $parseContent      whether or not to parse the content within these elements
     * integer        $nestLimit         an optional limit of the number of elements of this kind that can be nested
     *                                   within each other before the parser stops parsing them.
     * InputValidator $optionValidator   the validator to run {option} through
     * BodyValidator  $bodyValidator     the validator to run {param} through (only used if $parseContent == false)
     * 
     ****************************************************************************************************************
     * 
     * @param string  $tag               the tag name (for example the b in [b])
     * @param string  $html              the html to use, with {param} and optionally {option} for replacements
     * @param array   $parameters        an array of options (see above for options allowed)
     */
    protected function addDefinition($tag, $html, $parameters = array())
    {
        $useOption = (isset($parameters['use_option'])) ? $parameters['use_option'] : false;
        $parseContent = (isset($parameters['parse_content'])) ? $parameters['parse_content'] : true;
        $nestLimit = (isset($parameters['nest_limit'])) ? $parameters['nest_limit'] : -1;
        $optionValidator = (isset($parameters['option_validator']) && isset($this->validator[$parameters['option_validator']])) ?
            $this->validator[$parameters['option_validator']] : null;
        $bodyValidator = (isset($parameters['body_validator']) && isset($this->validator[$parameters['body_validator']])) ?
            $this->validator[$parameters['body_validator']] : null;

        $this->parser->addBBCode($tag, $html, $useOption, $parseContent, $nestLimit, $optionValidator, $bodyValidator);
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
