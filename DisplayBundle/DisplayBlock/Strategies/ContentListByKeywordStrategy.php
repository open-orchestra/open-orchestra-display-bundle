<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;

/**
 * Class ContentListByKeywordStrategy
 */
class ContentListByKeywordStrategy extends AbstractContentListStrategy
{
    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::CONTENT_LIST_BY_KEYWORD == $block->getComponent();
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'content_list_by_keyword';
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    protected function getContent($attributes)
    {
        $contents = $this->contentRepository->findByContentTag($attributes['contentKeyword']);

        return $contents;
    }
}
