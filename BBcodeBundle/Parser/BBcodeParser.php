<?php

namespace OpenOrchestra\BBcodeBundle\Parser;

use OpenOrchestra\BBcodeBundle\Parser\BBcodeParserInterface;
use JBBCode\Parser;
use JBBCode\CodeDefinition;
use OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorCollectionInterface;
use OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorInterface;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionCollectionInterface;

/**
 * Class BBcodeParser
 */
class BBcodeParser extends Parser implements BBcodeParserInterface
{
    protected $validators = array();
    protected $codes = array();

    /**
     * Add/Override validators described in container configuration
     * 
     * @param array $validators
     */
    public function loadValidatorsFromConfiguration($validators)
    {
        foreach ($validators as $key => $class) {
            if (class_exists($class)) {
                $this->validators[$key] = new $class();
            }
        }
    }

    /**
     * Add/Override validators described in a BBcodeValidatorCollectionInterface
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
                $parameters = (isset($definition['parameters'])) ? $definition['parameters'] : array();
                $optionValidator = (isset($parameters['option_validator']) && isset($this->validator[$parameters['option_validator']])) ?
                    array($this->validator[$parameters['option_validator']]) : array();
                $bodyValidator = (isset($parameters['body_validator']) && isset($this->validator[$parameters['body_validator']])) ?
                    $this->validator[$parameters['body_validator']] : null;
                $this->addCodeDefinition(
                    CodeDefinition::construct(
                        $definition['tag'],
                        $definition['html'],
                        (isset($parameters['use_option'])) ? $parameters['use_option'] : false,
                        (isset($parameters['parse_content'])) ? $parameters['parse_content'] : true,
                        (isset($parameters['nest_limit'])) ? $parameters['nest_limit'] : -1,
                        $optionValidator, // seems there's a bug here, validators should be instanciated here
                        $bodyValidator
                    )
                );
                $this->codes[$definition['tag']] = $definition['html'];
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
        foreach ($collection->getDefinitions() as $definition) {
            if ($definition instanceof CodeDefinition) {
                $this->addCodeDefinition($definition);
                $this->codes[$definition->getTagName()] = $definition->getReplacementText();
            }
        }
    }

    /**
     * Get all registered codes
     * 
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }
}
