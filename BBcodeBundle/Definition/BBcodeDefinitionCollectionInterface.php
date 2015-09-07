<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionInterface;

/**
 * Interface BBcodeDefinitionCollectionInterface
 *
 */
interface BBcodeDefinitionCollectionInterface
{
    /**
     * Get an array of BBcodeDefinitionInterface
     * 
     * @return array
     */
    public function getDefinitions();
}
