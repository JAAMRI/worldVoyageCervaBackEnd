<?php

namespace Zfzmyrjlmj\ServeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;

class ServeController extends Controller
{
    /**
     * @Get("serveme/initialconfiguration")
     * @return array|JsonResponse
     * @View
     */
    public function serveAction()
    {
        try {
            $default_countries = $this->getDefaultCountries();
            $default_languages = $this->getDefaultLanguages();
            $default_civilities = $this->getDefaultCivilities();
            $default_genders = $this->getDefaultGenders();
            $default_insurance_companies = $this->getDefaultInsuranceCompanies();
            $default_identification_types = $this->getDefaultIdentificationTypes();
            $default_referred_bys = $this->getDefaultReferredBys();
            $default_marital_status = $this->getDefaultMaritalStatus();
            $default_communication_preferences = $this->getDefaultCommunicationPreferences();
            $default_procedures = $this->getDefaultPocedures();
            $default_practitioners = $this->getDefaultPractitioners();
            $default_patient_procedure_status = $this->getDefaultPatientProcedureStatus();
            $default_payment_methods = $this->getDefaultPaymentMethods();

            return array(
                "default_countries" => $default_countries,
                "default_languages" => $default_languages,
                "default_civilities" => $default_civilities,
                "default_genders" => $default_genders,
                "default_insurance_companies" => $default_insurance_companies,
                "default_identification_types" => $default_identification_types,
                "default_referred_bys" => $default_referred_bys,
                "default_marital_status" => $default_marital_status,
                "default_communication_preferences" => $default_communication_preferences,
                "default_procedures" => $default_procedures,
                "default_practitioners" => $default_practitioners,
                "default_patient_procedure_status" => $default_patient_procedure_status,
                "default_payment_methods" => $default_payment_methods
            );
        }catch (\Exception $e) {
            return new JsonResponse([
                'Error' => $e->getMessage()
            ], 500);
        }

    }

    private function getDefaultCountries(){
        return  $this->getDoctrine()
                    ->getRepository("AppBundle:DefaultCountry")
                    ->createQueryBuilder('dc')
                    ->where("dc.active = :active ")
                    ->orderBy( 'dc.label', 'asc' )
                    ->getQuery()
                    ->setParameter('active', 1)
                    ->getResult();
    }

    private function getDefaultLanguages(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultLanguage")
            ->createQueryBuilder('dl')
            ->where("dl.active = :active ")
            ->orderBy( 'dl.label', 'asc' )
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }

    private function getDefaultCivilities(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultCivility")
            ->createQueryBuilder('dc')
            ->orderBy( 'dc.order', 'asc' )
            ->where("dc.active = :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }

    private function getDefaultGenders(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultGender")
            ->createQueryBuilder('dg')
            ->orderBy( 'dg.order', 'asc' )
            ->where("dg.active = :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }


    private function getDefaultInsuranceCompanies(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultInsuranceCompany")
            ->createQueryBuilder('dic')
            ->orderBy( 'dic.name', 'asc' )
            ->where("dic.active = :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }

    private function getDefaultIdentificationTypes(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultIdentificationType")
            ->createQueryBuilder('dit')
            ->orderBy( 'dit.order', 'asc' )
            ->where("dit.active = :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }

    private function getDefaultReferredBys(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultReferredBy")
            ->createQueryBuilder('drb')
            ->orderBy( 'drb.order', 'asc' )
            ->where("drb.active = :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }

    private function getDefaultMaritalStatus(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultMaritalStatus")
            ->createQueryBuilder('dms')
            ->orderBy( 'dms.order', 'asc' )
            ->where("dms.active = :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }


    private function getDefaultCommunicationPreferences(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultCommunicationPreference")
            ->createQueryBuilder('dcp')
            ->orderBy( 'dcp.order', 'asc' )
            ->where("dcp.active = :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }


    private function getDefaultPocedures(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultProcedure")
            ->createQueryBuilder('dp')
            ->orderBy( 'dp.order', 'asc' )
            ->where("dp.active = :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }

    private function getDefaultPractitioners(){
        $fields = array('u.lastname', 'u.title', 'u.firstname', 'u.userUniqueid');
        return  $this->getDoctrine()
            ->getRepository("AppBundle:User")
            ->createQueryBuilder('u')
            ->select($fields)
            ->orderBy( 'u.lastname', 'asc' )
            ->where("u.roles like :role ")
            ->getQuery()
            ->setParameter('role', '%PRACTITIONER%')
            ->getResult();
    }


    private function getDefaultPatientProcedureStatus(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultPatientProcedureStatus")
            ->createQueryBuilder('u')
            ->orderBy( 'u.order', 'asc' )
            ->where("u.active like :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }

    private function getDefaultPaymentMethods(){
        return  $this->getDoctrine()
            ->getRepository("AppBundle:DefaultPaymentMethod")
            ->createQueryBuilder('pm')
            ->orderBy( 'pm.order', 'asc' )
            ->where("pm.active like :active ")
            ->getQuery()
            ->setParameter('active', 1)
            ->getResult();
    }


    /**
     * @return \AppBundle\Entity\ZfzmyrjlmjCurrency[]|array
     */
    private function retCurrencies(){
        return $this->getDoctrine()->getRepository("AppBundle:ZfzmyrjlmjCurrency")->findAll();
    }

    /**
     * @return \AppBundle\Entity\ZfzmyrjlmjPaymentMethod[]|array
     */
    private function retPaymentMethods(){
        return $this->getDoctrine()->getRepository("AppBundle:ZfzmyrjlmjPaymentMethod")->findAll();
    }


}
