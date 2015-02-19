<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;

/**
 * Class VideoStrategy
 */
class VideoStrategy extends AbstractStrategy
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
        return DisplayBlockInterface::VIDEO === $block->getComponent();
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
        $template = 'PHPOrchestraDisplayBundle:Block/Video:show.html.twig';
        $parameters = array();

        if (isset($attributes['videoType'])) {
            switch($attributes['videoType'])
            {
                case 'youtube':
                    $template = 'PHPOrchestraDisplayBundle:Block/Video:youtube.html.twig';
                    $parameters = $this->getYoutubeParameters($block);
                    break;
                case 'dailymotion':
                    $template = 'PHPOrchestraDisplayBundle:Block/Video:dailymotion.html.twig';
                    $parameters = $this->getDailymotionParameters($block);
                    break;
                case 'vimeo':
                    $template = 'PHPOrchestraDisplayBundle:Block/Video:vimeo.html.twig';
                    $parameters = $this->getVimeoParameters($block);
                    break;
            }
        }

        return $this->render($template, $parameters);
    }

    protected function getYoutubeParameters(BlockInterface $block)
    {
        $attributes = $block->getAttributes();

        $initialize = array(
            'youtubeAutoplay' => false,
            'youtubeShowinfo' => false,
            'youtubeFs' => false,
            'youtubeRel' => false,
            'youtubeDisablekb' => false,
            'youtubeLoop' => false,
            'youtubeControls' => false,
            'youtubeTheme' => false,
            'youtubeColor' => false,
        );

        $attributes = array_merge($initialize, $attributes);

        $urlParams = array();
        foreach (array('youtubeAutoplay', 'youtubeShowinfo', 'youtubeFs', 'youtubeRel', 'youtubeDisablekb', 'youtubeLoop') as $key) {
            if ($attributes[$key] === true) {
                $urlParams[strtolower(substr($key, 7))] = 1;
            }
        }

        if ($attributes['youtubeControls'] === false) {
            $urlParams['controls'] = 0;
        }
        if ($attributes['youtubeTheme'] === true) {
            $urlParams['theme'] = 'light';
        }
        if ($attributes['youtubeColor'] === true) {
            $urlParams['color'] = 'white';
        }
        if ($attributes['youtubeHl'] !== '') {
            $urlParams['hl'] = $attributes['youtubeHl'];
        }

        $url = "//www.youtube.com/embed/" . $attributes['youtubeVideoId'] ."?" . http_build_query($urlParams, '', '&amp;');

        return array(
            'url' => $url,
            'class' => $block->getClass(),
            'id' => $block->getId(),
            'width' => $attributes['youtubeWidth'],
            'height' => $attributes['youtubeHeight']
        );
    }

    protected function getDailymotionParameters(BlockInterface $block)
    {
        return array();
    }

    /**
     * Return view parameters for a vimeo video
     * 
     * @param BlockInterface $block
     * 
     * @return array
     */
    protected function getVimeoParameters(BlockInterface $block)
    {
        $attributes = $block->getAttributes();

        $initialize = array(
            'vimeoAutoplay' => false,
            'vimeoTitle' => false,
            'vimeoFullscreen' => false,
            'vimeoByline' => false,
            'vimeoPortrait' => false,
            'vimeoLoop' => false,
            'vimeoBadge' => false,
            'vimeoColor' => false,
        );

        $attributes = array_merge($initialize, $attributes);

        $urlParams = array();

        foreach (array('vimeoAutoplay', 'vimeoFullscreen', 'vimeoLoop') as $key) {
            if ($attributes[$key] === true) {
                $urlParams[strtolower(substr($key, 5))] = 1;
            }
        }
        foreach (array('vimeoTitle', 'vimeoByline', 'vimeoPortrait', 'vimeoBadge') as $key) {
            if ($attributes[$key] === false) {
                $urlParams[strtolower(substr($key, 5))] = 0;
            }
        }
        if ($attributes['vimeoColor'] !== '') {
            $urlParams['color'] = str_replace('#', '', $attributes['vimeoColor']);
        }

        $url = "//player.vimeo.com/video/" . $attributes['vimeoVideoId'] ."?" . http_build_query($urlParams, '', '&amp;');

        return array(
            'url' => $url,
            'width' => $attributes['vimeoWidth'],
            'height' => $attributes['vimeoHeight']
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'video';
    }
}