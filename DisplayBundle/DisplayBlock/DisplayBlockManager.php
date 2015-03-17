<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock;

use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use OpenOrchestra\DisplayBundle\Exception\DisplayBlockStrategyNotFoundException;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\ModelInterface\Model\CacheableInterface;
use OpenOrchestra\DisplayBundle\Manager\TagManager;

/**
 * Class DisplayBlockManager
 */
class DisplayBlockManager
{
    protected $strategies = array();
    protected $cacheableManager;
    protected $templating;
    protected $tagManager;

    /**
     * @param EngineInterface  $templating
     * @param CacheableManager $cacheableManager
     */
    public function __construct(
        EngineInterface $templating,
        CacheableManager $cacheableManager,
        TagManager $tagManager
    ){
        $this->templating = $templating;
        $this->cacheableManager = $cacheableManager;
        $this->tagManager = $tagManager;
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

                $cacheTags = $strategy->getTags($block);
                $cacheTags[] = $this->tagManager->formatBlockTypeTag($block->getComponent());
                $this->cacheableManager->tagResponse($response, $cacheTags);

                $cacheStatus = CacheableInterface::CACHE_PRIVATE;
                if ($strategy->isPublic($block)) {
                    $cacheStatus = CacheableInterface::CACHE_PUBLIC;
                }

                $response = $this->cacheableManager->setResponseCacheParameters(
                    $response,
                    $block->getMaxAge(),
                    $cacheStatus
                );

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
