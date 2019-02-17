<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientBillingDetailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patientUniqueid')
            ->add('type')
            ->add('billed')
            ->add('received')
            ->add('paymentMethod')
            ->add('description')
            ->add('patientProcedureId')
            ->add('lastModified', 'datetime', array(
                'input' => 'datetime',
                'widget' => 'single_text'
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PatientBillingDetail',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }
}
