<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\Exception\NodeNotFoundException;
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
     *
     * @throws NodeNotFoundException
     */
    public function show(BlockInterface $block)
    {
        $nodes = null;
        $nodeId = $block->getAttribute('nodeName');

        if (!is_null($nodeId)) {
            $nodes = $this->nodeRepository->getSubMenu($nodeId, $block->getAttribute('nbLevel'), $this->request->getLocale());
        }

        if (!is_null($nodes)) {

            return $this->render(
                'OpenOrchestraDisplayBundle:Block/Menu:tree.html.twig',
                array(
                    'tree' => $nodes,
                    'id' => $block->getId(),
                    'class' => $block->getClass(),
                )
            );
        }

        throw new NodeNotFoundException($nodeId);
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
