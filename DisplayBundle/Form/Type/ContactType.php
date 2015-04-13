<?php

namespace OpenOrchestra\DisplayBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\True;

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
        $builder->add('name', 'text', array('label' => 'open_orchestra_display.contact.form.name'))
        ->add('email', 'email', array('label' => 'open_orchestra_display.contact.form.email'))
        ->add('subject', 'text', array('label' => 'open_orchestra_display.contact.form.subject'))
        ->add('message', 'textarea', array('label' => 'open_orchestra_display.contact.form.message'))
        ->add('captcha','checkbox', array(
            'label' => 'open_orchestra_display.contact.form.captcha',
            'constraints' => array(new True())
        ))
        ->add('send', 'submit', array('label' => 'open_orchestra_display.contact.form.send'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Contact';
    }
}
