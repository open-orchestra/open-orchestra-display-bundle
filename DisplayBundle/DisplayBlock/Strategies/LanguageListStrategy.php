<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LanguageListStrategy
 */
class LanguageListStrategy extends AbstractStrategy
{
    protected $builder;

    /**
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->builder = $formFactory->createBuilder('form');
    }

    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::LANGUAGE_LIST == $block->getComponent();
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

        $form = $this->builder->create('language_choice', 'choice', array(
            'choices' => $attributes['languages'],
            'preferred_choices' => array($attributes['default']),
        ))
        ->getForm();

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/LanguageList:show.html.twig',
            array(
                'form' => $form->createView(),
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
        return 'language_list';
    }
}
