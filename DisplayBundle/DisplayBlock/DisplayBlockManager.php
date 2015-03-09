<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock;

use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use OpenOrchestra\DisplayBundle\Exception\DisplayBlockStrategyNotFoundException;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use FOS\HttpCacheBundle\CacheManager;

/**
 * Class DisplayBlockManager
 */
class DisplayBlockManager
{
    protected $strategies = array();
    protected $cacheableManager;
    protected $cacheManager;
    protected $templating;

    /**
     * @param EngineInterface  $templating
     * @param CacheableManager $cacheableManager
     */
    public function __construct(
        EngineInterface $templating,
        CacheableManager $cacheableManager,
        CacheManager $cacheManager
    ){
        $this->templating = $templating;
        $this->cacheableManager = $cacheableManager;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param DisplayBlockInterface $strategy
     */
    public function addStrategy(DisplayBlockInterface $strategy)
    {
        $this->strategies[$strategy->getName()] = $strategy;
        $strategy->setManager($this);
    }

    /**
     * Perform the show action for a block
     *
     * @param BlockInterface $block
     *
     * @throws DisplayBlockStrategyNotFoundException
     * @return Response
     */
    public function show(BlockInterface $block)
    {
        /** @var DisplayBlockInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
                $response = $strategy->show($block);

                $this->cacheManager->tagResponse($response, array('poc', 'block'));

                $response = $this->cacheableManager->setMaxAge($block->getMaxAge(), $response);

                return $response;
            }
        }

        throw new DisplayBlockStrategyNotFoundException($block->getComponent());
    }

    /**
     * @return \Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine
     */
    public function getTemplating()
    {
        return $this->templating;
    }
}
