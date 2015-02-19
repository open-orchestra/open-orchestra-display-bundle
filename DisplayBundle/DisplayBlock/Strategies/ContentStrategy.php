<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use PHPOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
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
        return DisplayBlockInterface::CONTENT == $block->getComponent() &&
            is_array($this->request->get('module_parameters')) &&
            array_key_exists('newsId', $this->request->get('module_parameters'));
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
        $content = $this->contentRepository->findOneByContentId($this->request->get('module_parameters')['newsId']);

        if ($content != null) {
            return $this->render(
                'PHPOrchestraDisplayBundle:Block/Content:show.html.twig',
                array(
                    'content' => $content,
                    'class' => $block->getClass(),
                    'id' => $block->getId(),
                )
            );
        }

        return new Response();
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
