<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock;

use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DisplayBlockInterface
 */
interface DisplayBlockInterface
{
    /**
     * Check if the strategy support this block
     *
     * @param ReadBlockInterface $block
     *
     * @return boolean
     */
    public function support(ReadBlockInterface $block);

    /**
     * Indicate if the block is public or private
     *
     * @param ReadBlockInterface $block
     * 
     * @return boolean
     */
    public function isPublic(ReadBlockInterface $block);

    /**
     * Return block specific cache tags
     * 
     * @param  ReadBlockInterface $block
     * 
     * @return array
     */
    public function getCacheTags(ReadBlockInterface $block);

    /**
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block);

    /**
     * @param ReadBlockInterface $block
     *
     * @return string|null
     */
    public function toString(ReadBlockInterface $block);

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName();

    /**
     * Set the manager
     *
     * @param DisplayBlockManager $manager
     */
    public function setManager(DisplayBlockManager $manager);

    /**
     * Set the current site manager
     *
     * @param CurrentSiteIdInterface $currentSiteManager
     */
    public function setCurrentSiteManager(CurrentSiteIdInterface $currentSiteManager);
}
