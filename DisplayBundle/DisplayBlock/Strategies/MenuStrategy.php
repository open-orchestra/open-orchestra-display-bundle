<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use PHPOrchestra\ModelInterface\Repository\NodeRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class MenuStrategy
 */
class MenuStrategy extends AbstractStrategy
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
        $this->request = $requestStack->getMasterRequest();
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
        return DisplayBlockInterface::MENU == $block->getComponent();
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
        $nodes = $this->nodeRepository->getMenuTree($this->request->getLocale());

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/Menu:show.html.twig',
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
        return 'menu';
    }

}
