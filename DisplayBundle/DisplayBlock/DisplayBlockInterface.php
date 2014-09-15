<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock;

use PHPOrchestra\ModelBundle\Model\BlockInterface;
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
