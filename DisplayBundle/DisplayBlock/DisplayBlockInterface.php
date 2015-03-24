<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock;

use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DisplayBlockInterface
 */
interface DisplayBlockInterface
{
    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block);

    /**
     * Indicate if the block is public or private
     *
     * @param BlockInterface $block
     * 
     * @return boolean
     */
    public function isPublic(BlockInterface $block);

    /**
     * Return block specific tags
     * 
     * @param  BlockInterface $block
     * 
     * @return array
     */
    public function getTags(BlockInterface $block);

    /**
     * Perform the show action for a block
     *
     * @param BlockInterface $block
     *
     * @return Response
     */
    public function show(BlockInterface $block);

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
}
