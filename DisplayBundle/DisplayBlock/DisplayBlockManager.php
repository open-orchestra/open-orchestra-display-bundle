<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock;

use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use OpenOrchestra\DisplayBundle\Exception\DisplayBlockStrategyNotFoundException;
use OpenOrchestra\DisplayBundle\Manager\ContextInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\ModelInterface\Model\CacheableInterface;
use OpenOrchestra\BaseBundle\Manager\TagManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use OpenOrchestra\ModelInterface\Event\BlockEvent;
use OpenOrchestra\ModelInterface\BlockEvents;

/**
 * Class DisplayBlockManager
 */
class DisplayBlockManager
{
    protected $strategies = array();
    protected $cachedStrategies = array();
    protected $cacheableManager;
    protected $templating;
    protected $tagManager;
    protected $currentSiteIdInterface;
    protected $dispatcher;

    /**
     * @param EngineInterface          $templating
     * @param CacheableManager         $cacheableManager
     * @param TagManager               $tagManager
     * @param ContextInterface         $currentSiteIdInterface
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        EngineInterface $templating,
        CacheableManager $cacheableManager,
        TagManager $tagManager,
        ContextInterface $currentSiteIdInterface,
        EventDispatcherInterface $dispatcher
    ){
        $this->templating = $templating;
        $this->cacheableManager = $cacheableManager;
        $this->tagManager = $tagManager;
        $this->currentSiteIdInterface = $currentSiteIdInterface;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param DisplayBlockInterface $strategy
     */
    public function addStrategy(DisplayBlockInterface $strategy)
    {
        $this->strategies[$strategy->getName()] = $strategy;
        $strategy->setManager($this);
        $strategy->setCurrentSiteManager($this->currentSiteIdInterface);
    }

    /**
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @throws DisplayBlockStrategyNotFoundException
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        $strategy = $this->getStrategy($block);
        if (null === $strategy) {
            throw new DisplayBlockStrategyNotFoundException($block->getComponent());
        }

        $this->dispatcher->dispatch(
            BlockEvents::PRE_BLOCK_RENDER,
            new BlockEvent($block)
        );

        $response = $strategy->show($block);

        $cacheStatus = CacheableInterface::CACHE_PRIVATE;
        if ($strategy->isPublic($block)) {
            $cacheStatus = CacheableInterface::CACHE_PUBLIC;
        }

        $response = $this->cacheableManager->setResponseCacheParameters(
            $response,
            $block->getMaxAge(),
            $cacheStatus
        );

        $this->dispatcher->dispatch(
            BlockEvents::POST_BLOCK_RENDER,
            new BlockEvent($block, $response)
        );

        return $response;

    }

    /**
     * Get block cache tags
     * @param ReadBlockInterface $block
     *
     * @throws DisplayBlockStrategyNotFoundException
     *
     * @return array
     */
    public function getCacheTags(ReadBlockInterface $block)
    {
        $strategy = $this->getStrategy($block);
        if (null === $strategy) {
            throw new DisplayBlockStrategyNotFoundException($block->getComponent());
        }

        $cacheTags = $strategy->getCacheTags($block);
        $cacheTags[] = $this->tagManager->formatBlockTypeTag($block->getComponent());

        return $cacheTags;
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return array
     *
     * @throws DisplayBlockStrategyNotFoundException
     */
    public function getBlockParameter(ReadBlockInterface $block)
    {
        $strategy = $this->getStrategy($block);
        if (null === $strategy) {
            throw new DisplayBlockStrategyNotFoundException($block->getComponent());
        }

        return $strategy->getBlockParameter();
    }

    /**
     * Check the block cache policy
     *
     * @param ReadBlockInterface $block
     * 
     * @throws DisplayBlockStrategyNotFoundException
     *
     * @return bool
     */
    public function isPublic(ReadBlockInterface $block)
    {
        $strategy = $this->getStrategy($block);
        if (null === $strategy) {
            throw new DisplayBlockStrategyNotFoundException($block->getComponent());
        }

        return $strategy->isPublic($block);
    }

    /**
     * @return EngineInterface
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return null|DisplayBlockInterface
     */
    protected function getStrategy(ReadBlockInterface $block)
    {
        if (isset($this->cachedStrategies[$block->getId()])) {
            return $this->cachedStrategies[$block->getId()];
        }

        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
                if ($block->getId()) {
                    $this->cachedStrategies[$block->getId()] = $strategy;
                }

                return $strategy;
            }
        }

        return null;
    }
}
