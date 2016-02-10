<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\Event\MailerEvent;
use OpenOrchestra\DisplayBundle\Form\Type\ContactType;
use OpenOrchestra\DisplayBundle\MailerEvents;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ContactStrategy
 */
class ContactStrategy extends AbstractStrategy
{
    const NAME = 'contact';

    protected $formFactory;
    protected $request;
    protected $dispatcher;

    /**
     * @param FormFactory              $formFactory
     * @param RequestStack             $requestStack
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        FormFactory $formFactory,
        RequestStack $requestStack,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->formFactory = $formFactory;
        $this->request = $requestStack->getMasterRequest();
        $this->dispatcher = $dispatcher;
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
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @return Response
     */
    public function show(ReadBlockInterface $block)
    {
        $form = $this->formFactory->create(new ContactType(), null, array(
            'method' => 'POST',
        ));

        $form->handleRequest($this->request);
        if ($form->isValid()) {

            $recipient = $block->getAttribute("recipient");
            $signature = $block->getAttribute("signature");
            $formData = $form->getData();
            //send alert message to webmaster
            $messageToAdmin = \Swift_Message::newInstance()
                ->setSubject($formData['subject'])
                ->setFrom($formData['email'])
                ->setTo($recipient)
                ->setBody(
                    $this->renderView(
                        'OpenOrchestraDisplayBundle:Block/Email:show_admin.txt.twig',
                        array(
                            'name' => $formData['name'],
                            'message' => $formData['message'],
                            'mail' => $formData['email'],
                        )
                    )
                );
            $event = new MailerEvent($messageToAdmin);
            $this->dispatcher->dispatch(MailerEvents::SEND_MAIL, $event);

            //send confirm e-mail for the user
            $messageToUser = \Swift_Message::newInstance()
                ->setSubject('open_orchestra_display.contact.contact_received')
                ->setFrom($recipient)
                ->setTo($formData['email'])
                ->setBody(
                    $this->renderView(
                        'OpenOrchestraDisplayBundle:Block/Email:show_user.txt.twig',
                        array('signature' => $signature)
                    )
                );
            $event = new MailerEvent($messageToUser);
            $this->dispatcher->dispatch(MailerEvents::SEND_MAIL, $event);
        }

        return $this->render('OpenOrchestraDisplayBundle:Block/Contact:show.html.twig', array(
            'form' => $form->createView(),
            'id' => $block->getId(),
            'class' => $block->getClass(),
        ));
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
