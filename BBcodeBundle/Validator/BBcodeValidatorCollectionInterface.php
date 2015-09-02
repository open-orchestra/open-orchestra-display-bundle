<?php 

namespace OpenOrchestra\BBcodeBundle\Validator;

use JBBCode\InputValidator;

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
