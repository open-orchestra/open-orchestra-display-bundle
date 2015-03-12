<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock;

use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DisplayBlockInterface
 */
interface DisplayBlockInterface
{
    const CONTACT = 'contact';
    const CARROUSEL = 'carrousel';
    const FOOTER = 'footer';
    const MENU = 'menu';
    const SAMPLE = 'sample';
    const SEARCH = 'search';
    const SEARCH_RESULT = 'search_result';
    const TINYMCEWYSIWYG = 'tiny_mce_wysiwyg';
    const CONFIGURABLE_CONTENT = 'configurable_content';
    const SUBMENU = 'sub_menu';
    const CONTENT_LIST= 'content_list';
    const CONTENT = 'content';
    const LANGUAGE_LIST = 'language_list';
    const VIDEO = 'video';
    const GMAP = 'gmap';
    const ADDTHIS = 'add_this';
    const AUDIENCE_ANALYSIS = 'audience_analysis';

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
     * @return boolean
     */
    public function isPublic();

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
