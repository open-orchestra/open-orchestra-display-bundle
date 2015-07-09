<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class TinyMCEWysiwygStrategy
 */
class TinyMCEWysiwygStrategy extends AbstractStrategy
{
    const TINYMCEWYSIWYG = 'tiny_mce_wysiwyg';

    protected $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Check if the strategy support this block
     *
     * @param ReadBlockInterface $block
     *
     * @return boolean
     */
    public function support(ReadBlockInterface $block)
    {
        return self::TINYMCEWYSIWYG == $block->getComponent();
    }

    /**
     * Indicate if the block is public or private
     *
     * @param ReadBlockInterface $block
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
        $htmlContent = $this->parseForMedias($block->getAttribute('htmlContent'));

        return $this->render(
            'OpenOrchestraDisplayBundle:Block/TinyMCEWysiwyg:show.html.twig',
            array(
                'htmlContent' => $htmlContent,
                'id' => $block->getId(),
                'class' => $block->getClass()
            )
        );
    }

    /**
     * Parse html to update media tags
     * 
     * @param string $html
     * 
     * @return string
     */
    protected function parseForMedias($html)
    {
        return str_replace(
            '<img class="tinymce-media" src="../',
            '<img class="tinymce-media" src="' . $this->router->getContext()->getBaseUrl() . '/',
            $html
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'TinyMCEWysiwyg';
    }
}
