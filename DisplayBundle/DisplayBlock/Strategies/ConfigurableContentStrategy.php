<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ConfigurableContentStrategy
 */
class ConfigurableContentStrategy extends AbstractStrategy
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
        return DisplayBlockInterface::CONFIGURABLE_CONTENT == $block->getComponent();
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

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/ConfigurableContent:show.html.twig',
            array(
                'contentId' => $attributes['contentId']
            )
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'ConfigurableContent';
    }
}
