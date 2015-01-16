<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DailymotionStrategy
 */
class DailymotionStrategy extends AbstractStrategy
{
    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::DAILYMOTION === $block->getComponent();
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

        $urlParams = array();
        if ($attributes['autoplay'] === true) {
            $urlParams['autoplay'] = 1;
        }
        if ($attributes['info'] === true) {
            $urlParams['info'] = 1;
        }
        if ($attributes['background'] !== '') {
            $urlParams['background'] = $attributes['background'];
        }
        if ($attributes['foreground'] !== '') {
            $urlParams['foreground'] = $attributes['foreground'];
        }
        if ($attributes['highlight'] !== '') {
            $urlParams['highlight'] = $attributes['highlight'];
        }

        $url = "//www.dailymotion.com/embed/video/" . $attributes['videoId'] . "?" . http_build_query($urlParams);

        $parameters = array(
            'url' => $url,
            'class' => $attributes['class'],
            'id' => $attributes['id'],
            'width' => $attributes['width'],
            'height' => $attributes['height']
        );

        return $this->render('PHPOrchestraDisplayBundle:Block/Dailymotion:show.html.twig', $parameters);
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'dailymotion';
    }
}
