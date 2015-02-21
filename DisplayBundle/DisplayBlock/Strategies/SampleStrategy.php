<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SampleStrategy
 */
class SampleStrategy extends AbstractStrategy
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
        return DisplayBlockInterface::SAMPLE == $block->getComponent();
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
        $response = $this->render(
            'OpenOrchestraDisplayBundle:Block/Sample:show.html.twig',
            array(
                'title' => $block->getAttribute('title'),
                'author' => $block->getAttribute('author'),
                'news' => $block->getAttribute('news'),
                'parameters' => array(),
            )
        );

        $response->setPublic();
        $response->setSharedMaxAge(5);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'sample';
    }
}
