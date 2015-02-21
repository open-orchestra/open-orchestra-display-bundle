<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\Routing\PhpOrchestraRouter;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use OpenOrchestra\ModelInterface\Model\ContentInterface;
use OpenOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
use OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface;
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
        $contents = $this->contentRepository->findByContentTypeAndChoiceTypeAndKeywords($block->getAttribute('contentType'), $block->getAttribute('choiceType'), $block->getAttribute('keywords'));

        $contentFromTemplate = array();
        if (!is_null($block->getAttribute('contentTemplate'))) {
            $twig = new \Twig_Environment(new \Twig_Loader_String());
            /** @var ContentInterface $content */
            foreach ($contents as $content) {
                $contentFromTemplate[$content->getId()] = $twig->render($block->getAttribute('contentTemplate'), array('content' => $content));
            }
        }

        $parameters = array(
            'contents' => $contents,
            'class' => $block->getClass(),
            'id' => $block->getId(),
            'characterNumber' => $block->getAttribute('characterNumber'),
            'contentFromTemplate' => $contentFromTemplate,
        );

        if ('' != $block->getAttribute('contentNodeId')) {
            $parameters['contentNodeId'] = $this->nodeRepository->findOneByNodeIdAndLanguageWithPublishedAndLastVersionAndSiteId($block->getAttribute('contentNodeId'))->getId();
        }

        return $this->render('OpenOrchestraDisplayBundle:Block/ContentList:show.html.twig', $parameters);
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
