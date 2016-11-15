<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GmapStrategy
 */
class GmapStrategy extends AbstractStrategy
{
    const NAME = 'gmap';

    /**
     * Check if the strategy support this block
     *
     * @param ReadBlockInterface $block
     *
     * @return boolean
     */
    public function support(ReadBlockInterface $block)
    {
        return self::NAME === $block->getComponent();
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
        $parameters = array(
            'latitude' => $block->getAttribute('latitude'),
            'longitude' => $block->getAttribute('longitude'),
            'zoom' => $block->getAttribute('zoom'),
            'class' => $block->getStyle(),
            'id' => $block->getId()
        );

        return $this->render('OpenOrchestraDisplayBundle:Block/Gmap:show.html.twig', $parameters);
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
        return 'gmap';
    }
}
