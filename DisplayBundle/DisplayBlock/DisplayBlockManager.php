<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock;

use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use OpenOrchestra\DisplayBundle\Exception\DisplayBlockStrategyNotFoundException;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\ModelInterface\Model\CacheableInterface;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class DisplayBlockManager
 */
class DisplayBlockManager
{
    protected $strategies = array();
    protected $cacheableManager;
    protected $templating;
    protected $tagManager;
    protected $currentSiteIdInterface;

    /**
     * @param EngineInterface        $templating
     * @param CacheableManager       $cacheableManager
     * @param TagManager             $tagManager
     * @param CurrentSiteIdInterface $currentSiteIdInterface
     */
    public function __construct(
        EngineInterface $templating,
        CacheableManager $cacheableManager,
        TagManager $tagManager,
        CurrentSiteIdInterface $currentSiteIdInterface
    ){
        $this->templating = $templating;
        $this->cacheableManager = $cacheableManager;
        $this->tagManager = $tagManager;
        $this->currentSiteIdInterface = $currentSiteIdInterface;
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
        /** @var DisplayBlockInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
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

                return $response;
            }
        }

        throw new DisplayBlockStrategyNotFoundException($block->getComponent());
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
        /** @var DisplayBlockInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
                $cacheTags = $strategy->getCacheTags($block);
                $cacheTags[] = $this->tagManager->formatBlockTypeTag($block->getComponent());

                return $cacheTags;
            }
        }

        throw new DisplayBlockStrategyNotFoundException($block->getComponent());
    }

    /**
     * @return EngineInterface
     */
    public function getTemplating()
    {
        return $this->templating;
    }
}
