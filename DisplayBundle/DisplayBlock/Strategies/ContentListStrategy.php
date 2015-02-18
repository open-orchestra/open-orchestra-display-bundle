<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\DisplayBundle\Routing\PhpOrchestraRouter;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use PHPOrchestra\ModelInterface\Model\ContentInterface;
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

        $contentFromTemplate = array();
        if (array_key_exists('contentTemplate', $attributes) && !empty($attributes['contentTemplate'])) {
            $twig = new \Twig_Environment(new \Twig_Loader_String());
            /** @var ContentInterface $content */
            foreach ($contents as $content) {
                $contentFromTemplate[$content->getId()] = $twig->render($attributes['contentTemplate'], array('content' => $content));
            }
        }

        $parameters = array(
            'contents' => $contents,
            'class' => $block->getClass(),
            'id' => $block->getId(),
            'characterNumber' => $attributes['characterNumber'],
            'contentFromTemplate' => $contentFromTemplate,
        );

        if ('' != $attributes['contentNodeId']) {
            $parameters['contentNodeId'] = $this->nodeRepository->findOneByNodeIdAndLanguageWithPublishedAndLastVersionAndSiteId($attributes['contentNodeId'])->getId();
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
