<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\Exception\ContentNotFoundException;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use OpenOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContentStrategy
 */
class ContentStrategy extends AbstractStrategy
{
    protected $contentRepository;
    protected $request;

    /**
     * @param ContentRepositoryInterface $contentRepository
     * @param RequestStack               $requestStack
     */
    public function __construct(ContentRepositoryInterface $contentRepository, RequestStack $requestStack)
    {
        $this->contentRepository = $contentRepository;
        $this->request = $requestStack->getCurrentRequest();
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
        return DisplayBlockInterface::CONTENT == $block->getComponent();
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
        $content = null;

        if (!is_null($contentId)) {
            $content = $this->contentRepository->findOneByContentId($contentId);
        }
        if (is_null($content) && $this->request->get('token')) {
            $content = array('name' => 'name', 'attributes' => array());
        }

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
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'content';
    }
}
