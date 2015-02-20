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
        $template = 'PHPOrchestraDisplayBundle:Block/Video:show.html.twig';
        $parameters = array(
            'class' => $block->getClass(),
            'id' => $block->getId()
        );

        switch($block->getAttribute('videoType'))
        {
            case 'youtube':
                $template = 'PHPOrchestraDisplayBundle:Block/Video:youtube.html.twig';
                $parameters = array_merge(
                    $parameters,
                    $this->getYoutubeParameters($block)
                );
                break;

            case 'dailymotion':
                $template = 'PHPOrchestraDisplayBundle:Block/Video:dailymotion.html.twig';
                $parameters = array_merge(
                    $parameters,
                    $this->getDailymotionParameters($block)
                );
                break;

            case 'vimeo':
                $template = 'PHPOrchestraDisplayBundle:Block/Video:vimeo.html.twig';
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
     * @param BlockInterface $block
     * 
     * @return array
     */
    protected function getYoutubeParameters(BlockInterface $block)
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
     * @param BlockInterface $block
     * 
     * @return array
     */
    protected function getDailymotionParameters(BlockInterface $block)
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
     * @param BlockInterface $block
     * 
     * @return array
     */
    protected function getVimeoParameters(BlockInterface $block)
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
