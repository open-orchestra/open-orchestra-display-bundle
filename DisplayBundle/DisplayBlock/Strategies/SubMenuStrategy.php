<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\Exception\NodeNotFoundException;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class SubMenuStrategy
 */
class SubMenuStrategy extends AbstractStrategy
{
    const SUBMENU = 'sub_menu';

    protected $nodeRepository;
    protected $router;
    protected $request;
    protected $tagManager;

    /**
     * @param NodeRepositoryInterface $nodeRepository
     * @param UrlGeneratorInterface   $router
     * @param RequestStack            $requestStack
     * @param TagManager              $tagManager
     */
    public function __construct(
        NodeRepositoryInterface $nodeRepository,
        UrlGeneratorInterface $router,
        RequestStack $requestStack,
        TagManager $tagManager
    ){
        $this->nodeRepository = $nodeRepository;
        $this->router = $router;
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
        return self::SUBMENU == $block->getComponent();
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
     * @throws NodeNotFoundException
     */
    public function show(BlockInterface $block)
    {
        $nodes = $this->getNodes($block);

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

        throw new NodeNotFoundException($block->getAttribute('nodeName'));
    }

    /**
     * Get nodes to display
     * 
     * @param BlockInterface $block
     *
     * @return array
     */
    protected function getNodes(BlockInterface $block)
    {
        $nodes = null;
        $nodeName = $block->getAttribute('nodeName');

        if (!is_null($nodeName)) {
            $nodes = $this->nodeRepository->getSubMenu($nodeName, $block->getAttribute('nbLevel'), $this->request->getLocale());
        }

        return $nodes;
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

        $nodes = $this->getNodes($block);

        if ($nodes) {
            foreach ($nodes as $node) {
                $tags[] = $this->tagManager->formatNodeIdTag($node->getNodeId());
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
        return 'sub_menu';
    }
}
