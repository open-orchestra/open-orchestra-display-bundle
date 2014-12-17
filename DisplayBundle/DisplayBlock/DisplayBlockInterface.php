<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock;

use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DisplayBlockInterface
 */
interface DisplayBlockInterface
{
    const CONTACT = 'contact';
    const CARROUSEL = 'carrousel';
    const FOOTER = 'footer';
    const HEADER = 'header';
    const MENU = 'menu';
    const NEWS = 'news';
    const SAMPLE = 'sample';
    const SEARCH = 'search';
    const SEARCH_RESULT = 'search_result';
    const TINYMCEWYSIWYG = 'tiny_mce_wysiwyg';
    const CONFIGURABLE_CONTENT = 'configurable_content';
    const SUBMENU = 'sub_menu';
    const CONTENT_LIST_BY_TYPE= 'content_list_by_type';
    const CONTENT_LIST_BY_KEYWORD = 'content_list_by_keyword';
    const CONTENT = 'content';
    const LANGUAGE_LIST = 'language_list';
    const MEDIA_LIST_BY_KEYWORD = 'media_list_by_keyword';

    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block);

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
