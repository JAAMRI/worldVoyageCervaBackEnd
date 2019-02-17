<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientProcedureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('practitionerUniqueid')
            ->add('patientUniqueid')
            ->add('cost')
            ->add('duration')
            ->add('postDelay')
            ->add('label')
            ->add('doneOn', 'datetime', array(
                'input' => 'datetime',
                'widget' => 'single_text'
            ))
            ->add('code')
            ->add('status')
            ->add('appointment', 'datetime', array(
                'input' => 'datetime',
                'widget' => 'single_text'
            ))
            ->add('addedBy')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PatientProcedure',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }
}
