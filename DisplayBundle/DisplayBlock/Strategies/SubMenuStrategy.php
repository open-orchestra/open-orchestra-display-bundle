<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class SubMenuStrategy
 */
class SubMenuStrategy extends AbstractStrategy
{
    protected $nodeRepository;
    protected $router;
    protected $request;

    /**
     * @param NodeRepositoryInterface $nodeRepository
     * @param UrlGeneratorInterface   $router
     * @param RequestStack            $requestStack
     */
    public function __construct(NodeRepositoryInterface $nodeRepository, UrlGeneratorInterface $router, RequestStack $requestStack)
    {
        $this->nodeRepository = $nodeRepository;
        $this->router = $router;
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
        $nodes = $this->nodeRepository->getSubMenu($block->getAttribute('node'), $block->getAttribute('nbLevel'), $this->request->getLocale());

        return $this->render(
            'OpenOrchestraDisplayBundle:Block/Menu:show.html.twig',
            array(
                'tree' => $nodes,
                'id' => $block->getId(),
                'class' => $block->getClass(),
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
