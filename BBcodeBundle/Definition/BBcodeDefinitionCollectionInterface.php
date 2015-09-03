<?php 

namespace OpenOrchestra\BBcodeBundle\Definition;

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
