<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

/**
 * Interface BBcodeDefinitionInterface
 *
 */
interface BBcodeDefinitionInterface
{
    /**
     * Get the tag name of the code (for example the b in [b])
     * 
     * @return string
     */
    public function getTag();

    /**
     * Get the html to use, with {param} and optionally {option} for replacements
     * 
     * @return string
     */
    public function getHtml();

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
    public function getParameters();
}
