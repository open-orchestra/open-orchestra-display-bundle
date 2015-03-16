<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\Exception\ContentNotFoundException;
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
     * Indicate if the block is public or private
     * 
     * @return boolean
     */
    public function isPublic(BlockInterface $block)
    {
        return true;
    }

    /**
     * Perform the show action for a block
     *
     * @param BlockInterface $block
     *
     * @return Response
     *
     * @throws ContentNotFoundException
     */
    public function show(BlockInterface $block)
    {
        $contents = $this->getContents($block->getAttribute('contentType'), $block->getAttribute('choiceType'), $block->getAttribute('keywords'));

        if (!is_null($contents)) {

            $contentFromTemplate = array();
            if ($block->getAttribute('contentTemplateEnabled') == 1 && !is_null($block->getAttribute('contentTemplate'))) {
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

        throw new ContentNotFoundException();
    }

    /**
     * Return block contents
     * 
     * @param string $contentType
     * @param string $choiceType
     * @param string $keyword
     * 
     * @return array
     */
    protected function getContents($contentType, $choiceType, $keyword)
    {
        return $this->contentRepository->findByContentTypeAndChoiceTypeAndKeywords($contentType, $choiceType, $keyword);
    }

    /**
     * Return block specific tags
     * 
     * @param BlockInterface $block
     * 
     * @return array
     */
    public function getTags(BlockInterface $block)
    {
        $tags = array();

        $contents = $this->getContents($block->getAttribute('contentType'), $block->getAttribute('choiceType'), $block->getAttribute('keywords'));

        if ($contents) {
            foreach ($contents as $content) {
                $tags[] = 'contentId-' . $content->getId();
                if (!in_array('contentType-' . $content->getContentType(), $tags)) {
                    $tags[] = 'contentType-' . $content->getContentType();
                }
            }
        }

        return $tags;
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
