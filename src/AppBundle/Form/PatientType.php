<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('homephone')
            ->add('cellphone')
            ->add('officephone')
            ->add('firstname')
            ->add('lastname')
            ->add('middlename')
            ->add('city')
            ->add('country')
            ->add('state')
            ->add('postalcode')
            ->add('address')
            ->add('email')
            ->add('gender')
            ->add('birthdate', 'datetime', array(
                'input' => 'datetime',
                'widget' => 'single_text'
            ))
            ->add('allergies')
            ->add('drugIntolerance')
            ->add('smokingStatus')
            ->add('isPregnant')
            ->add('isBreastFeeding')
            ->add('emergencycontactname1')
            ->add('emergencycontactphone1')
            ->add('referredby')
            ->add('diagnosis')
            ->add('notes')
            ->add('username')
            ->add('password')
            ->add('title')
            ->add('identificationtype')
            ->add('identificationnumber')
            ->add('status')
            ->add('reminderLanguage')
            ->add('medicalHistoryConsent')
            ->add('preferedLanguage')
            ->add('maritalStatus')
            ->add('insuredPersonName')
            ->add('insuredPersonPhone')
            ->add('insuredPersonIdentificationType')
            ->add('insuredPersonIdentificationNumber')
            ->add('insuranceCompanyId')
            ->add('insurancePolicyNumber')
            ->add('profession')
            ->add('rating')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Patient',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }
}
