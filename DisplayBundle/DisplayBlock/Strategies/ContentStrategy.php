<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use PHPOrchestra\ModelBundle\Repository\ContentRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContentStrategy
 */
class ContentStrategy extends AbstractStrategy
{
    protected $contentRepository;
    protected $container;

    /**
     * @param ContentRepository $contentRepository
     * @param Container         $container
     */
    public function __construct(ContentRepository $contentRepository, Container $container)
    {
        $this->contentRepository = $contentRepository;
        $this->container = $container;
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
     * Perform the show action for a block
     *
     * @param BlockInterface $block
     *
     * @return Response
     */
    public function show(BlockInterface $block)
    {
        $attributes = $block->getAttributes();
        $request = $this->getRequest();

        $criteria = array(
            'contentId' => $request->query->get('contentId')
        );

        $content = $this->contentRepository->findOneBy($criteria);

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/Content:show.html.twig',
            array(
                'content' => $content,
                'class' => $attributes['class'],
                'id' => $attributes['id'],
            )
        );
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->container->get('request');
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
