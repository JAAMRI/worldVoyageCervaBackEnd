<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', 'datetime', array(
                'input' => 'datetime',
                'widget' => 'single_text'
            ))
            ->add('end', 'datetime', array(
                'input' => 'datetime',
                'widget' => 'single_text'
            ))
            ->add('color')
            ->add('procedures')
            ->add('title')
            ->add('lastname')
            ->add('phone')
            ->add('type')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }
}
