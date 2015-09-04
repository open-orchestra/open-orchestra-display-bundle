<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionInterface;
use JBBCode\InputValidator;

/**
 * Class BBcodeDefinition
 */
class BBcodeDefinition implements BBcodeDefinitionInterface
{
    protected $tag;
    protected $html;
    protected $parameters = array();

    /**
     * @param string $tag
     * @param string $html
     * boolean        $useOption
     * boolean        $parseContent
     * integer        $nestLimit
     * InputValidator $optionValidator
     * InputValidator $bodyValidator
     */
    public function __construct ($tag, $html, $useOption = false, $parseContent = true,
                                $nestLimit = -1, InputValidator $optionValidator = null, InputValidator $bodyValidator = null
    ){
        $this->tag = $tag;
        $this->html = $html;
        $this->parameters['use_option'] = $useOption;
        $this->parameters['parse_content'] = $parseContent;
        $this->parameters['nest_limit'] = $nestLimit;
        $this->parameters['option_validator'] = $optionValidator;
        $this->parameters['body_validator'] = $bodyValidator;
    }

    /**
     * Get the tag name of the code (for example the b in [b])
     * 
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Get the html to use, with {param} and optionally {option} for replacements
     * 
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Get parameters to use when building the definition. These can be:
     * use_option        true | false
     * parse_content     true | false
     * nest_limit        allowed nested limit
     * body_validator    namespace of the validator to use 
     * option_validator  namespace of the validator to use
     * 
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
