<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use PHPOrchestra\ModelBundle\Repository\NodeRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class SubMenuStrategy
 */
class SubMenuStrategy extends AbstractStrategy
{
    protected $nodeRepository;
    protected $router;

    /**
     * @param NodeRepository        $nodeRepository
     * @param UrlGeneratorInterface $router
     * @param Container             $container
     */
    public function __construct(NodeRepository $nodeRepository, UrlGeneratorInterface $router, Container $container)
    {
        $this->nodeRepository = $nodeRepository;
        $this->router = $router;
        $this->request = $container->get('request');
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
        return DisplayBlockInterface::SUBMENU == $block->getComponent();
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
        $nodes = $this->nodeRepository->getSubMenu($attributes['node'], $attributes['nbLevel'], $this->$request->getLocale());

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/Menu:show.html.twig',
            array(
                'tree' => $nodes,
                'id' => $attributes['id'],
                'class' => $attributes['class'],
            )
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'sub_menu';
    }
}
