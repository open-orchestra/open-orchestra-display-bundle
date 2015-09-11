<?php 

namespace OpenOrchestra\BBcodeBundle\Validator;

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
}
