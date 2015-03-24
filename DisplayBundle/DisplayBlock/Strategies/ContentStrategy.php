<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\Exception\ContentNotFoundException;
use OpenOrchestra\DisplayBundle\Fake\FakeContent;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use OpenOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\BaseBundle\Manager\TagManager;
use OpenOrchestra\ModelBundle\Document\Content;

/**
 * Class ContentStrategy
 */
class ContentStrategy extends AbstractStrategy
{
    const CONTENT = 'content';

    protected $contentRepository;
    protected $request;
    protected $tagManager;

    /**
     * @param ContentRepositoryInterface $contentRepository
     * @param RequestStack               $requestStack
     * @param TagManager   $tagManager
     */
    public function __construct(
        ContentRepositoryInterface $contentRepository,
        RequestStack $requestStack,
        TagManager $tagManager
    ){
        $this->contentRepository = $contentRepository;
        $this->request = $requestStack->getCurrentRequest();
        $this->tagManager = $tagManager;
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
        return self::CONTENT == $block->getComponent();
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
     * @param BlockInterface $block
     *
     * @return Response
     *
     * @throws ContentNotFoundException
     */
    public function show(BlockInterface $block)
    {
        $contentId = $this->request->get('contentId');

        $content = $this->getContent($contentId);

        if (!is_null($content)) {
            $contentFromTemplate = null;
            if ($block->getAttribute('contentTemplateEnabled') == 1 && !is_null($block->getAttribute('contentTemplate'))) {
                $twig = new \Twig_Environment(new \Twig_Loader_String());
                $contentFromTemplate = $twig->render($block->getAttribute('contentTemplate'), array('content' => $content));
            }

            return $this->render(
                'OpenOrchestraDisplayBundle:Block/Content:show.html.twig',
                array(
                    'contentFromTemplate' => $contentFromTemplate,
                    'content' => $content,
                    'class' => $block->getClass(),
                    'id' => $block->getId(),
                )
            );
        }

        throw new ContentNotFoundException($contentId);
    }

    /**
     * Get content to display
     * 
     * @param string $contentId
     * 
     * @return Content
     */
    protected function getContent($contentId)
    {
        $content = null;

        if (!is_null($contentId)) {
            $content = $this->contentRepository->findOneByContentId($contentId);
        }

        if (is_null($content) && $this->request->get('token')) {
            $content = new FakeContent();
        }

        return $content;
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
        $contentId = $this->request->get('contentId');

        $content = $this->getContent($contentId);

        return array(
            $this->tagManager->formatContentIdTag($contentId),
            $this->tagManager->formatContentTypeTag($content->getContentType()),
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'content';
    }
}
