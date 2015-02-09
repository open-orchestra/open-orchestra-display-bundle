<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\DisplayBundle\Routing\PhpOrchestraRouter;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use PHPOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
use PHPOrchestra\ModelInterface\Repository\NodeRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContentListStrategy
 */
class ContentListStrategy extends AbstractStrategy
{
    protected $contentRepository;
    protected $nodeRepository;
    protected $request;

    /**
     * @param ContentRepositoryInterface $contentRepository
     * @param NodeRepositoryInterface    $nodeRepository
     */
    public function __construct(ContentRepositoryInterface $contentRepository, NodeRepositoryInterface $nodeRepository)
    {
        $this->contentRepository = $contentRepository;
        $this->nodeRepository = $nodeRepository;
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
        $attributes = $block->getAttributes();
        $contents = $this->contentRepository->findByContentTypeAndChoiceTypeAndKeywords($attributes['contentType'], $attributes['choiceType'], $attributes['keywords']);

        $parameters = array(
            'contents' => $contents,
            'class' => $attributes['class'],
            'id' => $attributes['id'],
            'characterNumber' => $attributes['characterNumber'],
        );

        if ('' != $attributes['newsNodeId']) {
            $parameters['newsNodeId'] = $this->nodeRepository->findOneByNodeIdAndLanguageWithPublishedAndLastVersionAndSiteId($attributes['newsNodeId'])->getId();
        }

        return $this->render('PHPOrchestraDisplayBundle:Block/ContentList:show.html.twig', $parameters);
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
        return DisplayBlockInterface::CONTENT_LIST === $block->getComponent();
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'content_list';
    }
}
