<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class YoutubeStrategy
 */
class YoutubeStrategy extends AbstractStrategy
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
        return DisplayBlockInterface::YOUTUBE === $block->getComponent();
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
        if ($attributes['showinfo'] === true) {
            $urlParams['showinfo'] = 1;
        }
        if ($attributes['fs'] === true) {
            $urlParams['fs'] = 1;
        }
        if ($attributes['rel'] === true) {
            $urlParams['rel'] = 1;
        }
        if ($attributes['disablekb'] === true) {
            $urlParams['disablekb'] = 1;
        }
        if ($attributes['loop'] === true) {
            $urlParams['loop'] = 1;
        }
        if ($attributes['controls'] === false) {
            $urlParams['controls'] = 0;
        }
        if ($attributes['theme'] === true) {
            $urlParams['theme'] = 'light';
        }
        if ($attributes['color'] === true) {
            $urlParams['color'] = 'white';
        }
        if ($attributes['hl'] !== '') {
            $urlParams['hl'] = $attributes['hl'];
        }

        $url = "//www.youtube.com/embed/" . $attributes['videoId'] ."?" . http_build_query($urlParams, '', '&amp;');

        $parameters = array(
            'url' => $url,
            'class' => $attributes['class'],
            'id' => $attributes['id'],
            'width' => $attributes['width'],
            'height' => $attributes['height']
        );

        return $this->render('PHPOrchestraDisplayBundle:Block/Youtube:show.html.twig', $parameters);
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'youtube';
    }
}
