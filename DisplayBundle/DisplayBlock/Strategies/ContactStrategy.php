<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\Form\Type\ContactType;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ContactStrategy
 */
class ContactStrategy extends AbstractStrategy
{
    protected $formFactory;
    protected $router;

    /**
     * @param FormFactory           $formFactory
     * @param UrlGeneratorInterface $router
     */
    public function __construct(FormFactory $formFactory, UrlGeneratorInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
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
        return DisplayBlockInterface::CONTACT == $block->getComponent();
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
        $form = $this->formFactory->create(new ContactType(), null, array(
            'action' => $this->router->generate('open_orchestra_display_contact_send'),
            'method' => 'POST',
        ));

        return $this->render(
            'OpenOrchestraDisplayBundle:Block/Contact:show.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'contact';
    }

}
