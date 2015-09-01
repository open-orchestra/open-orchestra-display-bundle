<?php

namespace OpenOrchestra\BBcodeBundle\Parser;

use JBBCode\CodeDefinitionSet;
use JBBCode\CodeDefinitionBuilder;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionInterface;
use OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorInterface;

class ConfiguredCodeDefinitionSet implements CodeDefinitionSet
{
    protected $definitions;
    protected $validators = array();

    const VALIDATOR_TAG_NAME = 'open_orchestra_bbcode.validator';
    const CODE_DEFINITION_TAG_NAME = 'open_orchestra_bbcode.code_definition';

    /**
     * Class constructor
     * 
     * @param array $codeDefinitions
     * @param array $validators
     */
    public function __construct($codeDefinitions, $validators)
    {
        $this->loadValidators($validators);
        $this->loadDefinitions($codeDefinitions);
    }

    /**
     * Add/Override a validator via a tagged service
     * 
     * @param BBcodeValidatorInterface $validator
     */
    protected function addValidator(BBcodeValidatorInterface $validator)
    {
        $this->validators[$validator->getName()] = $validator;
    }

    /**
     * Add/Override a definition via a tagged service
     */
    protected function addDefinition(BBcodeDefinitionInterface $definition)
    {
        $this->loadDefinition($definition->getTag(), $defintion->getHtml(), $definition->getParameters());
    }

    /**
     * Load validators
     * 
     * @param array $validators
     */
    protected function loadValidators($validators) {
        foreach ($validators as $key => $class) {
            if (class_exists($class)) {
                $this->validators[$key] = new $class();
            }
        }
    }

    /**
     * Load tag definitions
     * 
     * @param array $codeDefinitions
     */
    protected function loadDefinitions($codeDefinitions)
    {
        foreach ($codeDefinitions as $definition) {
            if (isset($definition['tag']) && isset($definition['html'])) {
                $parameters = array();
                if (isset($definition['parameters'])) {
                    $parameters = $definition['parameters'];
                }
                $this->loadDefinition($definition['tag'], $definition['html'], $parameters);
            }
        }
    }

    /**
     * Load a definition
     * 
     * @param string $tag
     * @param string $html
     * @param array  $parameters
     */
    protected function loadDefinition($tag, $html, $parameters = array())
    {
        $builder = new CodeDefinitionBuilder($tag, $html);

        if (isset($parameters['use_option'])) {
            $builder = $builder->setUseOption($parameters['use_option']);
        }
        if (isset($parameters['parse_content'])) {
            $builder = $builder->setParseContent($parameters['parse_content']);
        }
        if (isset($parameters['body_validator'])) {
            $builder = $builder->setBodyValidator($this->validator[$parameters['body_validator']]);
        }
        if (isset($parameters['option_validator'])) {
            $builder = $builder->setOptionValidator($this->validator[$parameters['option_validator']]);
        }

        array_push($this->definitions, $builder->build());
    }

    /**
     * Retrieves the CodeDefinitions within this set as an array
     * 
     * @return array
     */
    public function getCodeDefinitions()
    {
        return $this->definitions;
    }
}