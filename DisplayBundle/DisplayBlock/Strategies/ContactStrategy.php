<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\Form\Type\ContactType;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ContactStrategyGet
 */
class ContactStrategy extends AbstractStrategy
{
    const CONTACT = 'contact';

    protected $formFactory;
    protected $router;
    protected $request;

    /**
     * @param FormFactory           $formFactory
     * @param UrlGeneratorInterface $router
     * @param RequestStack $requestStack
     */
    public function __construct(FormFactory $formFactory, UrlGeneratorInterface $router, RequestStack $requestStack)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->request = $requestStack->getCurrentRequest();
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
        return self::CONTACT == $block->getComponent();
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
            'action' => '',
            'method' => 'POST',
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $formData = $form->getData();
            //send alert message to webmaster
            $messageToAdmin = \Swift_Message::newInstance()
                ->setSubject($formData['subject'])
                ->setFrom($formData['email'])
                ->setTo($formData['recipient'])
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
            $this->get('mailer')->send($messageToAdmin);

            //send confirm e-mail for the user
            $messageToUser = \Swift_Message::newInstance()
                ->setSubject($this->get('translator')->trans('open_orchestra_display.contact.contact_received'))
                ->setFrom($formData['recipient'])
                ->setTo($formData['email'])
                ->setBody(
                    $this->renderView(
                        'OpenOrchestraDisplayBundle:Block/Email:show_user.txt.twig',
                        array('signature' => $formData['signature'])
                    )
                );
            $this->get('mailer')->send($messageToUser);

            $messageSendEmail = $this->get('translator')->trans('open_orchestra_display.contact.send_message_ok');
        }

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
