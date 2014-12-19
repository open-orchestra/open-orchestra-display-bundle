<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use PHPOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NewsStrategy
 */
class NewsStrategy extends AbstractStrategy
{
    protected $contentRepository;

    /**
     * @param ContentRepositoryInterface $contentRepository
     */
    public function __construct(ContentRepositoryInterface $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::NEWS == $block->getComponent();
    }

    /**
     * Perform the show action for a block
     *
     * @param BlockInterface $block
     *
     * @return Response
     */
    public function show(BlockInterface $block)
    {
        $allNews = $this->contentRepository->findAllNews();

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/News:show.html.twig',
            array('allNews' => $allNews)
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'news';
    }
}
