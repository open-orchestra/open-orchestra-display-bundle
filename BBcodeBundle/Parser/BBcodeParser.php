<?php

namespace OpenOrchestra\BBcodeBundle\Parser;

use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition;
use JBBCode\ElementNode;
use JBBCode\Tokenizer;
use OpenOrchestra\BBcodeBundle\Parser\BBcodeParserInterface;
use JBBCode\Parser;
use OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorCollectionInterface;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionCollectionInterface;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeDocumentElement;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeTextNode;

/**
 * Class BBcodeParser
 */
class BBcodeParser extends Parser implements BBcodeParserInterface
{
    protected $validators = array();

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
            $this->validators[$validator->getName()] = $validator;
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
                    new BBcodeDefinition(
                        $definition['tag'],
                        $definition['html'],
                        (isset($parameters['use_option'])) ? $parameters['use_option'] : false,
                        (isset($parameters['parse_content'])) ? $parameters['parse_content'] : true,
                        (isset($parameters['nest_limit'])) ? $parameters['nest_limit'] : -1,
                        $optionValidator, // seems there's a bug here, validators should be instanciated here
                        $bodyValidator
                    )
                );
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
            $this->addCodeDefinition($definition);
        }
    }

    /**
     * Get all registered codes
     * 
     * @return array
     */
    public function getCodes()
    {
        return $this->bbcodes;
    }

    /**
     * Get html from BBcode
     * 
     * @return string
     */
    public function getAsPreviewHTML()
    {
        return $this->treeRoot->getAsPreviewHTML();
    }

    /**
     * Removes the old parse tree if one exists.
     */
    protected function reset()
    {
        $this->treeRoot = new BBcodeDocumentElement();
        $this->nextNodeid = 1;
    }

    /**
     * Creates a new text node with the given parent and text string.
     *
     * @param $parent  the parent of the text node
     * @param $string  the text of the text node
     *
     * @return TextNode the newly created TextNode
     */
    protected function createTextNode(ElementNode $parent, $string)
    {
        if (count($parent->getChildren())) {
            $children = $parent->getChildren();
            $lastElement = end($children);
            reset($children);

            if ($lastElement->isTextNode()) {
                $lastElement->setValue($lastElement->getValue() . $string);
                return $lastElement;
            }
        }

        $textNode = new BBcodeTextNode($string);
        $textNode->setNodeId(++$this->nextNodeid);
        $parent->addChild($textNode);
        return $textNode;
    }

    /**
     * This is the next step in parsing a tag. It's possible for it to still be invalid at this
     * point but many of the basic invalid tag name conditions have already been handled.
     *
     * @param ElementNode $parent  the current parent element
     * @param Tokenizer   $tokenizer  the tokenizer we're using
     * @param string      $tagContent  the text between the [ and the ], assuming there is actually a ]
     *
     * @return ElementNode the new parent element
     */
    protected function parseTag(ElementNode $parent, Tokenizer $tokenizer, $tagContent)
    {
        $next;
        if (!$tokenizer->hasNext() || ($next = $tokenizer->next()) != ']') {
            $this->createTextNode($parent, '[');
            $this->createTextNode($parent, $tagContent);
            return $parent;
        }

        list($tmpTagName, $options) = $this->parseOptions($tagContent);

        $actualTagName;
        if ('' != $tmpTagName && '/' == $tmpTagName[0]) {
           $actualTagName = substr($tmpTagName, 1);
        } else {
            $actualTagName = $tmpTagName;
        }

        if ('' != $tmpTagName && '/' == $tmpTagName[0]) {
            $elToClose = $parent->closestParentOfType($actualTagName);
            if (null === $elToClose || count($options) > 1)
            if (null === $elToClose || count($options) > 1) {
                $this->createTextNode($parent, '[');
                $this->createTextNode($parent, $tagContent);
                $this->createTextNode($parent, ']');
                return $parent;
            } else {
                return $elToClose->getParent();
            }
        }

        if ('' == $actualTagName || !$this->codeExists($actualTagName, !empty($options))) {
            $this->createTextNode($parent, '[');
            $this->createTextNode($parent, $tagContent);
            $this->createTextNode($parent, ']');
            return $parent;
        }

        $el = new BBcodeElementNode();
        $el->setNodeId(++$this->nextNodeid);
        $code = $this->getCode($actualTagName, !empty($options));
        $el->setBBCodeDefinition($code);
        if (!empty($options)) {
            $el->setAttribute($options);
        }
        $parent->addChild($el);

        return $el;
    }
}
