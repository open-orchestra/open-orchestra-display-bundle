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
        $contentId = '';
        if (is_array($this->request->get('module_parameters')) && array_key_exists('newsId', $this->request->get('module_parameters'))) {

            $contentId = $this->request->get('module_parameters')['newsId'];
            $content = $this->contentRepository->findOneByContentId($contentId);

            if ($content != null) {
                return $this->render(
                    'OpenOrchestraDisplayBundle:Block/Content:show.html.twig',
                    array(
                        'content' => $content,
                        'class' => $block->getClass(),
                        'id' => $block->getId(),
                    )
                );
            }
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
