<?php

namespace OpenOrchestra\DisplayBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $builder->add('captcha','checkbox', array(
            'label' => 'open_orchestra_display.contact.form.captcha',
            'constraints' => array(new True())
        ));
        $builder->add('recipient', 'hidden', array(
            'constraints' => array(new NotBlank())
        ));
        $builder->add('signature', 'hidden');

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
