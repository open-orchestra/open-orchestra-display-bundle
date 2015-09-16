<?php 

namespace OpenOrchestra\BBcodeBundle\Validator;

use OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorInterface;

/**
 * Interface BBcodeValidatorCollection
 *
 */
interface BBcodeValidatorCollectionInterface
{
    /**
     * Get an array of BBcodeValidatorInterface
     * 
     * @return array
     */
    public function getValidators();

    /**
     * Add a BBcodeValidatorInterface
     * 
     * @param BBcodeValidatorInterface $validator
     */
    public function addValidator(BBcodeValidatorInterface $validator);
}
