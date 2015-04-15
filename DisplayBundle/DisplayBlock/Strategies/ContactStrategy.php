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
    protected $request;
    protected $mailer;
    protected $router;

    /**
     * @param FormFactory           $formFactory
     * @param UrlGeneratorInterface $router
     * @param RequestStack $requestStack
     */
    public function __construct(
        FormFactory $formFactory,
        UrlGeneratorInterface $router,
        RequestStack $requestStack,
        $mailer
    )
    {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->formFactory = $formFactory;
        $this->request = $requestStack->getMasterRequest();
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

// return new Response(var_dump($this->request));
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
            $this->mailer->send($messageToAdmin);

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
            $this->mailer->send($messageToUser);
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
