<?php

namespace OpenOrchestra\DisplayBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Gregwar\CaptchaBundle\Type\CaptchaType;

/**
 * Class ContactType
 */
class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => 'open_orchestra_display.contact.form.name',
            'constraints' => array(new NotBlank())
        ));
        $builder->add('email', 'email', array(
            'label' => 'open_orchestra_display.contact.form.email'
        ));
        $builder->add('subject', 'text', array(
            'label' => 'open_orchestra_display.contact.form.subject',
            'constraints' => array(new NotBlank())
        ));
        $builder->add('message', 'textarea', array(
            'label' => 'open_orchestra_display.contact.form.message',
            'constraints' => array(new NotBlank())
        ));
        $builder->add('captcha', CaptchaType::class, array(
            'label' => 'open_orchestra_display.contact.form.captcha'
        ));
        $builder->add('send', 'submit', array('label' => 'open_orchestra_display.contact.form.send'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Contact';
    }
}
