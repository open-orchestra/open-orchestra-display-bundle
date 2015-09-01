<?php 

namespace OpenOrchestra\BBcodeBundle\Validator;

use JBBCode\InputValidator;

/**
 * Interface BBcodeValidator
 *
 */
interface BBcodeValidatorInterface extends InputValidator
{
    /**
     * Get the validator name
     */
    public function getName();
}
