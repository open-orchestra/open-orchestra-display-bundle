<?php 

namespace OpenOrchestra\BBcodeBundle\Parser;

use OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorCollectionInterface;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionCollectionInterface;

/**
 * Interface BBcodeParserInterface
 *
 */
interface BBcodeParserInterface
{
    /**
     * Add/Override validators described in container configuration
     * 
     * @param array $validators
     */
	public function loadValidatorsFromConfiguration($validators);

    /**
     * Add/Override validators described in BBcodeValidatorCollectionInterface
     * 
     * @param BBcodeValidatorCollectionInterface $validator
     */
    public function loadValidatorsFromService(BBcodeValidatorCollectionInterface $collection);

    /**
     * Add/Override tag definitions described in container configuration
     * 
     * @param array $codeDefinitions
     */
    public function loadDefinitionsFromConfiguration($codeDefinitions);

    /**
     * Add/Override definitions described in a BBcodeDefinitionCollectionInterface
     * 
     * @param BBcodeDefinitionCollectionInterface $collection
     */
    public function loadDefinitionsFromService(BBcodeDefinitionCollectionInterface $collection);

    /**
     * Get html from BBcode
     * 
     * @return string
     */
    public function getAsHTML();

    /**
     * Get html from BBcode, in a preview context
     * 
     * @return string
     */
    public function getAsPreviewHTML();

    /**
     * Parse BBcode to fix unclosed tags
     * 
     * @return string
     */
    public function getAsBBCode();

    /**
     * Remove all BBcode to get raw text
     * 
     * @return string
     */
    public function getAsText();

    /**
     * Get all registered codes
     * 
     * @return array
     */
    public function getCodes();
}
