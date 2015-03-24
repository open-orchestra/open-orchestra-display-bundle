<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\DisplayBlock\Strategies\AbstractStrategy;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AddThisStrategy
 */
class AddThisStrategy extends AbstractStrategy
{
    const ADDTHIS = 'add_this';

    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return self::ADDTHIS === $block->getComponent();
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
        $parameters = array(
            'pubid' => $block->getAttribute('pubid'),
            'class' => $block->getClass(),
            'id' => $block->getId(),
            'addThisClass' => $block->getAttribute('addThisClass'),
        );

        return $this->render('OpenOrchestraDisplayBundle:Block/AddThis:show.html.twig', $parameters);
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'add_this';
    }
}
