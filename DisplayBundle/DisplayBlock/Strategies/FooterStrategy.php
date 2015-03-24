<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class FooterStrategy
 */
class FooterStrategy extends AbstractStrategy
{
    const FOOTER = 'footer';

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
        $this->request = $requestStack->getMasterRequest();
        $this->tagManager = $tagManager;
    }

    /**
     * Check if the strategy support this block
     *
     * @param ReadBlockInterface $block
     *
     * @return boolean
     */
    public function support(ReadBlockInterface $block)
    {
        return self::FOOTER == $block->getComponent();
    }

    /**
     * Indicate if the block is public or private
     * 
     * @return boolean
     */
    public function isPublic(ReadBlockInterface $block)
    {
        return true;
    }

    /**
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        $nodes = $this->getNodes();

        return $this->render(
            'OpenOrchestraDisplayBundle:Block/Footer:show.html.twig',
            array(
                'tree' => $nodes,
                'id' => $block->getId(),
                'class' => $block->getClass(),
            )
        );
    }

    /**
     * Get nodes to display
     * 
     * @return array
     */
    protected function getNodes()
    {
        return $this->nodeRepository->getFooterTree($this->request->getLocale());
    }

    /**
     * Return block specific tags
     * 
     * @param ReadBlockInterface $block
     * 
     * @return array
     */
    public function getTags(ReadBlockInterface $block)
    {
        $tags = array();

        $nodes = $this->getNodes();

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
        return 'footer';
    }
}
