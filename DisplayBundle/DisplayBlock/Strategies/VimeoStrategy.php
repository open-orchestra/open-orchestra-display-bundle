<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VimeoStrategy
 */
class VimeoStrategy extends AbstractStrategy
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
        return DisplayBlockInterface::VIMEO === $block->getComponent();
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
        if ($attributes['title'] === true) {
            $urlParams['title'] = 1;
        }
        if ($attributes['fullscreen'] === true) {
            $urlParams['fullscreen'] = 1;
        }
        if ($attributes['byline'] === true) {
            $urlParams['byline'] = 1;
        }
        if ($attributes['portrait'] === true) {
            $urlParams['portrait'] = 1;
        }
        if ($attributes['loop'] === true) {
            $urlParams['loop'] = 1;
        }
        if ($attributes['color'] !== '') {
            $urlParams['color'] = $attributes['color'];
        }

        $url = "//player.vimeo.com/video/" . $attributes['videoId'] ."?" . http_build_query($urlParams, '', '&amp;');

        $parameters = array(
            'url' => $url,
            'class' => $attributes['class'],
            'id' => $attributes['id'],
            'width' => $attributes['width'],
            'height' => $attributes['height']
        );

        return $this->render('PHPOrchestraDisplayBundle:Block/Vimeo:show.html.twig', $parameters);
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'vimeo';
    }
}
