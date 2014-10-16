<?php

namespace PHPOrchestra\DisplayBundle\Controller;

use PHPOrchestra\DisplayBundle\Form\Type\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;

/**
 * Class ContactController
 */
class ContactController extends Controller
{
    /**
     * Function send a email
     *
     * @param Request $request
     *
     * @Config\Route("/contact/send", name="php_orchestra_display_contact_send")
     * @Config\Method({"POST"})
     *
     * @return RedirectResponse
     */
    public function contactMailSendAction(Request $request)
    {
        $mailAdmin = $this->container->getParameter('php_orchestra_display.administrator_contact_email');

        $form = $this->createForm(new ContactType());

        $form->handleRequest($request);
        if ($form->isValid()) {
            $formData = $form->getData();
            //send alert message to webmaster
            $messageToAdmin = \Swift_Message::newInstance()
                ->setSubject($formData['subject'])
                ->setFrom($formData['email'])
                ->setTo($mailAdmin)
                ->setBody(
                    $this->renderView(
                        'PHPOrchestraDisplayBundle:Block/Email:show_admin.txt.twig',
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
                ->setSubject($this->get('translator')->trans('php_orchestra_display.contact.contact_received'))
                ->setFrom($mailAdmin)
                ->setTo($formData['email'])
                ->setBody(
                    $this->renderView(
                        'PHPOrchestraDisplayBundle:Block/Email:show_user.txt.twig',
                        array('name' => $this->container->getParameter('php_orchestra_display.contact_signature_email'))
                    )
                );
            $this->get('mailer')->send($messageToUser);
        }

        return $this->redirect($this->generateUrl('orchestra_page_home'));
    }
}
