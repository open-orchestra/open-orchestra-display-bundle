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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ContactStrategy
 */
class ContactStrategy extends AbstractDisplayBlockStrategy
{
    const NAME = 'contact';

    protected $urlGenerator;
    protected $formFactory;
    protected $requestStack;
    protected $dispatcher;

    /**
     * @param UrlGeneratorInterface    $urlGenerator
     * @param FormFactory              $formFactory
     * @param RequestStack             $requestStack
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        FormFactory $formFactory,
        RequestStack $requestStack,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
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
        $currentRequest = $this->requestStack->getCurrentRequest();

        $routeAttributes = array_merge(
            $currentRequest->get('_route_params'),
            array(
                'previous' => $currentRequest->get('currentRouteName'),
                'aliasId'  => $currentRequest->get('aliasId'),
            )
        );

        $form = $this->formFactory->create(
            ContactType::class,
            null,
            array(
                'method' => 'POST',
                'action' => $this->urlGenerator->generate(
                    'open_orchestra_front_block',
                    $routeAttributes
                )
            )
        );

        $form->handleRequest($currentRequest);

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
            'class' => $block->getStyle(),
        ));
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
     * @return array
     */
    public function getBlockParameter()
    {
        return array('post_data', 'request.aliasId');
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
