<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\BBcodeBundle\Parser\BBcodeParserInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TinyMCEWysiwygStrategy
 */
class TinyMCEWysiwygStrategy extends AbstractDisplayBlockStrategy
{
    const NAME = 'tiny_mce_wysiwyg';

    protected $parser;
    protected $requestStack;

    /**
     * @param BBcodeParserInterface $parser
     */
    public function __construct(BBcodeParserInterface $parser, RequestStack $requestStack)
    {
        $this->parser = $parser;
        $this->requestStack = $requestStack;
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
        return self::NAME == $block->getComponent();
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
        $htmlContent = $block->getAttribute('htmlContent');
        $this->parser->parse($htmlContent);
        $htmlContent = $this->parser->getAsHTML();

        return $this->render(
            'OpenOrchestraDisplayBundle:Block/TinyMCEWysiwyg:show.html.twig',
            array(
                'htmlContent' => $htmlContent,
                'id' => $block->getId(),
                'class' => $block->getStyle(),
            )
        );
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return array
     */
    public function getCacheTags(ReadBlockInterface $block)
    {
        return array();
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
