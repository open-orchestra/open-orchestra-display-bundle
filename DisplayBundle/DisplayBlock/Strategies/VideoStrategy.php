<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VideoStrategy
 */
class VideoStrategy extends AbstractStrategy
{
    const VIDEO = 'video';

    /**
     * Check if the strategy support this block
     *
     * @param ReadBlockInterface $block
     *
     * @return boolean
     */
    public function support(ReadBlockInterface $block)
    {
        return self::VIDEO === $block->getComponent();
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
        $template = 'OpenOrchestraDisplayBundle:Block/Video:show.html.twig';
        $parameters = array(
            'class' => $block->getClass(),
            'id' => $block->getId()
        );

        switch($block->getAttribute('videoType'))
        {
            case 'youtube':
                $template = 'OpenOrchestraDisplayBundle:Block/Video:youtube.html.twig';
                $parameters = array_merge(
                    $parameters,
                    $this->getYoutubeParameters($block)
                );
                break;

            case 'dailymotion':
                $template = 'OpenOrchestraDisplayBundle:Block/Video:dailymotion.html.twig';
                $parameters = array_merge(
                    $parameters,
                    $this->getDailymotionParameters($block)
                );
                break;

            case 'vimeo':
                $template = 'OpenOrchestraDisplayBundle:Block/Video:vimeo.html.twig';
                $parameters = array_merge(
                    $parameters,
                    $this->getVimeoParameters($block)
                );
                break;
        }

        return $this->render($template, $parameters);
    }

    /**
     * Return view parameters for a youtube video
     * 
     * @param ReadBlockInterface $block
     * 
     * @return array
     */
    protected function getYoutubeParameters(ReadBlockInterface $block)
    {
        $urlParams = array();
        foreach (array('youtubeAutoplay', 'youtubeShowinfo', 'youtubeFs', 'youtubeRel', 'youtubeDisablekb', 'youtubeLoop') as $key) {
            if ($block->getAttribute($key) === true) {
                $urlParams[strtolower(substr($key, 7))] = 1;
            }
        }

        if ($block->getAttribute('youtubeControls') === false) {
            $urlParams['controls'] = 0;
        }
        if ($block->getAttribute('youtubeTheme') === true) {
            $urlParams['theme'] = 'light';
        }
        if ($block->getAttribute('youtubeColor') === true) {
            $urlParams['color'] = 'white';
        }
        if ($block->getAttribute('youtubeHl') !== '') {
            $urlParams['hl'] = $block->getAttribute('youtubeHl');
        }

        $url = "//www.youtube.com/embed/" . $block->getAttribute('youtubeVideoId') ."?" . http_build_query($urlParams, '', '&amp;');

        return array(
            'url' => $url,
            'class' => $block->getClass(),
            'id' => $block->getId(),
            'width' => $block->getAttribute('youtubeWidth'),
            'height' => $block->getAttribute('youtubeHeight')
        );
    }

    /**
     * Return view parameters for a dailymotion video
     * 
     * @param ReadBlockInterface $block
     * 
     * @return array
     */
    protected function getDailymotionParameters(ReadBlockInterface $block)
    {
        $urlParams = array();

        foreach (array('dailymotionAutoplay', 'dailymotionChromeless') as $key) {
            if ($block->getAttribute($key) === true) {
                $urlParams[strtolower(substr($key, 11))] = 1;
            }
        }
        foreach (array('dailymotionLogo', 'dailymotionInfo', 'dailymotionRelated') as $key) {
            if ($block->getAttribute($key) === false) {
                $urlParams[strtolower(substr($key, 11))] = 0;
            }
        }
        foreach (array('dailymotionBackground', 'dailymotionForeground', 'dailymotionHighlight', 'dailymotionQuality') as $key) {
            if ($block->getAttribute($key) !== '') {
                $urlParams[strtolower(substr($key, 11))] = $block->getAttribute($key);
            }
        }

        $url = "//www.dailymotion.com/embed/video/" . $block->getAttribute('dailymotionVideoId') . "?" . http_build_query($urlParams);

        return array(
            'url' => $url,
            'width' => $block->getAttribute('dailymotionWidth'),
            'height' => $block->getAttribute('dailymotionHeight')
        );
    }

    /**
     * Return view parameters for a vimeo video
     * 
     * @param ReadBlockInterface $block
     * 
     * @return array
     */
    protected function getVimeoParameters(ReadBlockInterface $block)
    {
        $urlParams = array();

        foreach (array('vimeoAutoplay', 'vimeoFullscreen', 'vimeoLoop') as $key) {
            if ($block->getAttribute($key) === true) {
                $urlParams[strtolower(substr($key, 5))] = 1;
            }
        }
        foreach (array('vimeoTitle', 'vimeoByline', 'vimeoPortrait', 'vimeoBadge') as $key) {
            if ($block->getAttribute($key) === false) {
                $urlParams[strtolower(substr($key, 5))] = 0;
            }
        }
        if ($block->getAttribute('vimeoColor') !== '') {
            $urlParams['color'] = str_replace('#', '', $block->getAttribute('vimeoColor'));
        }

        $url = "//player.vimeo.com/video/" . $block->getAttribute('vimeoVideoId') ."?" . http_build_query($urlParams, '', '&amp;');

        return array(
            'url' => $url,
            'width' => $block->getAttribute('vimeoWidth'),
            'height' => $block->getAttribute('vimeoHeight')
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
