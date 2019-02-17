<?php

namespace Zfzmyrjlmj\PatientBundle\Controller;

use AppBundle\Entity\PatientBillingDetail;
use AppBundle\Entity\PatientProcedure;
use AppBundle\Form\PatientBillingDetailTypse;
use AppBundle\Form\PatientProcedureType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use AppBundle\Entity\Patient;
use AppBundle\Form\PatientType;
use Doctrine\DBAL\Driver\PDOException;


class DefaultController extends Controller
{

    /**
     * @Post("api/patients")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function newpatientAction(Request $request)
    {
        $req_data = json_decode($request->getContent(), true);
        $patient_data = $req_data['patient'];

        //Implode communication preferences
        if(isset($patient_data['communicationPreference']))
        {
            $patient_data['communicationPreference'] = $patient_data['communicationPreference'] = implode('|', $patient_data['communicationPreference']);
        }

        //Check if patient exists already (if username exists)
        if(isset($patient_data['username'])) {
            if ($this->doesPatientExist(array("username" => $patient_data['username']))) {
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => "Patient already exists"
                ], 409);
            }
        }//End if

        try {
            //Validate data and insert new User if data is valid
            $patient = new Patient();
            $form = $this->createForm(new PatientType(), $patient);

            $form->submit($patient_data);

            if ($form->isValid()) {
                //Set the patient unique id
                $patient->setPatientUniqueid( sprintf('%s-%s', date('Y') . date('m') . date('d'), strtoupper( uniqid()) ) );
                //Get user who served this patient
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $patient->setDoneBy($user->getId());

                try {
                    //Insert new user account
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($patient);
                    $em->flush();
                    return array("patientUniqueid" => $patient->getPatientUniqueid());
                }catch (PDOException $e){
                    return new JsonResponse([
                        'error' => 'bad_request', 'error_description' => $e->getMessage()
                    ], $e->getCode());
                }
            }
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => $form->getErrorsAsString()
            ], 400);


        }catch (\Exception $e){
            return new JsonResponse([
                'error' => 'exception_error', 'error_description' => $e->getMessage()
            ], 500);
        }
    }


    public function doesPatientExist($search_arr)
    {
        if($patient = $this->getDoctrine()->getRepository("AppBundle:Patient")->findOneBy($search_arr) )
        {
            return $patient;
        }
        return false;
    }


    /**
     * @Get("api/patients/{patient_uniqueid}")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function returnPatientAction($patient_uniqueid)
    {
        if($patient = $this->doesPatientExist(array("patientUniqueid" => $patient_uniqueid ) )){
            return $patient;
        }else{
            return new JsonResponse([
                'error' => 'not_found', 'error_description' => "Patient not found"
            ], 404);
        }
    }

    /**
     * @Put("api/patients/{patient_uniqueid}")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function updatePatientFileAction($patient_uniqueid, Request $request)
    {
        try {
            $req_data = json_decode($request->getContent(), true);
            $patient_data = $req_data['patient'];

            //Implode communication preferences
            if(isset($patient_data['communicationPreference']))
            {
                $patient_data['communicationPreference'] = $patient_data['communicationPreference'] = implode('|', $patient_data['communicationPreference']);
            }
            //Check of patient exists
            $patient = $this->doesPatientExist(array("patientUniqueid" => $patient_uniqueid));

            if (!$patient) {//This patient doesn't exist
                return new JsonResponse([
                    'error' => 'bad_not_found', 'error_description' => "Patient not found"
                ], 404);
            } else {

                $form = $this->createForm(new PatientType(), $patient);
                $form->submit($patient_data);

                if ($form->isValid()) {
                    try {
                        $em = $this->getDoctrine()->getEntityManager();
                        $em->persist($patient);
                        $em->flush();
                        return $patient;
                    } catch (PDOException $e) {
                        return new JsonResponse([
                            'error' => 'bad_request', 'error_description' => $e->getMessage()
                        ], 400);
                    }
                }
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => $form->getErrorsAsString()
                ], 400);
            }
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'server_error', 'error_description' => $e->getMessage()
            ], 500);
        }

    }


    /**
     * @Get("api/patients/search/{q}")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function returnPatientsAction($q)
    {
        $patients = $this->getDoctrine()->getRepository("AppBundle:Patient")->createQueryBuilder('p')
            ->where("p.firstname like :searched ")
            ->orWhere("p.lastname like :searched ")
            ->orWhere("p.homephone like :searched ")
            ->orWhere("p.officephone like :searched ")
            ->orWhere("p.cellphone like :searched ")
            ->orWhere("p.patientUniqueid like :searched ")
            ->orWhere("p.identificationnumber like :searched ")
            ->setParameter('searched', "%" . $q . "%")
            ->getQuery()
            ->getResult();

        if (!$patients) {
            return new JsonResponse([
                'message' => 'nothing_found', 'message_description' => 'Nothing Found'
            ], 204);
        }
        return array("patients" => $patients, "total_patients" => count($patients)) ;
    }


    /////////////////////////////////////////////////////////////////////////////////////////////
    //patient Procedures

    /**
     * @Post("api/patients/{patientUniqueid}/procedures")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function newpatientProcedureAction($patientUniqueid, Request $request)
    {
        $req_data = json_decode($request->getContent(), true);
        $patient_procedure_data = $req_data['patientProcedure'];

        try {
            $patientProcedure = new PatientProcedure();
            $form = $this->createForm(new PatientProcedureType(), $patientProcedure);

            $form->submit($patient_procedure_data);

            if ($form->isValid()) {
                try {
                    $patientProcedure->setPatientUniqueid($patientUniqueid);
                    //Get user who added this procedure
                    $user = $this->get('security.token_storage')->getToken()->getUser();
                    $patientProcedure->setAddedBy($user->getId());

                    //Insert new procedure
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($patientProcedure);
                    $em->flush();
                    //Add patient procedure to patient billing detail
                    $new_patient_billing_detail = array("patientUniqueid" => $patientProcedure->getPatientUniqueid(), "patientProcedureId" => $patientProcedure->getId(), "type" => "PATIENT_PROCEDURE", "description" => $patientProcedure->getLabel());
                    if( $this->addNewPatientBillingDetail($new_patient_billing_detail) ){ return $patientProcedure; }
                    else{
                        //remove inserted procedure and Throw errors
                        $this->deletePatientProcedureAction($patientProcedure->getPatientUniqueid(),  $patientProcedure->getId() );
                        return new JsonResponse([
                            'error' => 'bad_request', 'error_description' => "Error inserting billing detail"
                        ], 500);
                    }

                }catch (PDOException $e){
                    return new JsonResponse([
                        'error' => 'bad_request', 'error_description' => $e->getMessage()
                    ], $e->getCode());
                }
            }
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => $form->getErrorsAsString()
            ], 400);


        }catch (\Exception $e){
            return new JsonResponse([
                'error' => 'exception_error', 'error_description' => $e->getMessage()
            ], 500);
        }
    }

    private function addNewPatientBillingDetail($new_patient_billing_detail_data){
        try {
            $patient_billing_detail = new PatientBillingDetail();
            $form = $this->createForm(new PatientBillingDetailType(), $patient_billing_detail);

            $form->submit($new_patient_billing_detail_data);

            if ($form->isValid()) {
                //Update last modified
                $patient_billing_detail->setLastModified(new \DateTime());
                //Insert new procedure
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($patient_billing_detail);
                $em->flush();
                return $patient_billing_detail;
            }
            return $form->getErrorsAsString();
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }


    /**
     * @Get("api/patients/{patient_uniqueid}/procedures")
     * @param $patient_uniqueid
     * @View
     */
    public function returnPatientProceduresAction($patient_uniqueid)
    {
        $patient_procedures =  $this->getDoctrine()
            ->getRepository("AppBundle:PatientProcedure")
            ->createQueryBuilder('pp')
            ->where('pp.patientUniqueid = :searched' )
            ->orderBy( 'pp.id', 'desc' )
            ->setParameter('searched', $patient_uniqueid)
            ->getQuery()
            ->getResult();

        if($patient_procedures){
            return $patient_procedures;
        }
        return new JsonResponse([
            "message" => "nothing_found", "message_description" => "Nothing Found"
        ], 204);
    }


    /**
     * @Get("api/patients/procedures/appointments/{dateV}")
     * @param $dateV
     * @View
     */
    public function returnPatientProceduresByDateAction($dateV)
    {
        $patient_procedures =  $this->getDoctrine()
            ->getRepository("AppBundle:PatientProcedure")
            ->createQueryBuilder('pp')
            ->where('pp.appointment like :searched')
            ->orderBy( 'pp.appointment', 'asc' )
            ->setParameter('searched', "%" . $dateV . "%")
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);



        if($patient_procedures){
            //Temporary
            //Add patient photo, title, first name lastname
            $newpatient_procedures = array();
            foreach ($patient_procedures as $patient_procedure)
            {
                //$patient_profile = $this->getDoctrine()->getRepository('AppBundle:Patient')->findBy(array("patientUniqueid" => $patient_procedure['patientUniqueid'] ));
                $patient_profile =  $this->getDoctrine()
                    ->getRepository("AppBundle:Patient")
                    ->createQueryBuilder('p')
                    ->select("p.title", "p.firstname", "p.lastname", "p.photo", "p.cellphone")
                    ->where('p.patientUniqueid = :searched')
                    ->setParameter('searched', $patient_procedure['patientUniqueid'])
                    ->getQuery()
                    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                $newpatient_procedure = array_merge($patient_profile, $patient_procedure);
                array_push($newpatient_procedures, $newpatient_procedure);


            }
            //End temporary
            return $newpatient_procedures;
            //return $patient_procedures;
        }
        return new JsonResponse([
            "message" => "nothing_found", "message_description" => "Nothing Found"
        ], 204);
    }


    /**
     * @Put("api/patients/procedures/{patient_procedure_id}")
     * @param Request $request
     * @param $patient_procedure_id
     * @View
     */
    public function updatePatientProcedureAction($patient_procedure_id, Request $request )
    {
        $req_data = json_decode($request->getContent(), true);
        $patient_procedure_data = $req_data['patientProcedure'];

        //Make sure the patient procedure exists
        $patient_procedure = $this->getDoctrine()->getRepository("AppBundle:PatientProcedure")->findOneBy(
            array("id" => $patient_procedure_id)
        );

        if(!$patient_procedure or ($patient_procedure_id != $patient_procedure_data['id']) ) {
            return new JsonResponse([
                "message" => "nothing_found", "message_description" => "Procedure not Found"
            ], 404);
        }
        //Prevent a hack if status is already done or checked out and the cost changed
        $cost = $patient_procedure->getCost();
        if( ($patient_procedure->getStatus() == "_PATIENT_PROCEDURE_DONE" or $patient_procedure->getStatus() == "_PATIENT_PROCEDURE_CHECKED_OUT") && ($patient_procedure->getCost() != $patient_procedure_data['cost']) ){
            return new JsonResponse([
                "message" => "nothing_found", "message_description" => "We can not change procedure cost, it's already done or checked out"
            ], 403);
        }

        //Set doneOn date if this is a _PATIENT_PROCEDURE_DONE or _PATIENT_PROCEDURE_CHECKED_OUT update
        $doneOn = null;
        if(isset($patient_procedure_data['status'])) {
            if ($patient_procedure_data['status'] == '_PATIENT_PROCEDURE_DONE') {
                $doneOn = new \DateTime();
            } else if (($patient_procedure->getDoneOn() != null) && ($patient_procedure_data['status'] == '_PATIENT_PROCEDURE_CHECKED_OUT')) {
                $doneOn = $patient_procedure->getDoneOn();
            }//
            else if (($patient_procedure->getDoneOn() == null) && ($patient_procedure_data['status'] == '_PATIENT_PROCEDURE_CHECKED_OUT')) {
                $doneOn = new \DateTime();
            } else if ($patient_procedure_data['status'] != '_PATIENT_PROCEDURE_CHECKED_OUT') {
                $doneOn = null;
            }
        }//End if isset $patient_procedure_data['status']

        try {
            $form = $this->createForm(new PatientProcedureType(), $patient_procedure);
            $form->submit($patient_procedure_data);

            if ($form->isValid()) {
                try {
                    $patient_procedure->setDoneOn($doneOn);//Set DoneOn based on the actual status
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($patient_procedure);
                    $em->flush();
                    //Update patient billing if status is done or checked_out
                    if( $patient_procedure->getStatus() == "_PATIENT_PROCEDURE_DONE" or $patient_procedure->getStatus() == "_PATIENT_PROCEDURE_CHECKED_OUT" ) {//Update billed in patient billing detail if status is done or checked_out
                        $this->updatePatientBillingDetailWhenPatientProcedure($patient_procedure->getId(), $patient_procedure->getCost());
                    }//end if
                    else{//Bring billed to null in patient billing detail
                        $this->updatePatientBillingDetailWhenPatientProcedure($patient_procedure->getId(), null);
                    }
                    return $patient_procedure;
                }catch (PDOException $e){
                    return new JsonResponse([
                        'error' => 'bad_request', 'error_description' => $e->getMessage()
                    ], $e->getCode());
                }
            }
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => $form->getErrorsAsString()
            ], 400);


        }catch (\Exception $e){
            return new JsonResponse([
                'error' => 'exception_error', 'error_description' => $e->getMessage()
            ], 500);
        }

    }


    private function updatePatientBillingDetailWhenPatientProcedure($patient_procedure_id, $new_billed){
        //Find patient procedure
        $patient_billing_detail = $this->getDoctrine()->getRepository("AppBundle:PatientBillingDetail")->findOneBy(
            array("patientProcedureId" => $patient_procedure_id)
        );
        if($patient_billing_detail) {
            $patient_billing_detail->setLastModified( new \DateTime() );
            $patient_billing_detail->setBilled($new_billed);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($patient_billing_detail);
            $em->flush();
            return true;
        }
        return false;
    }

    /**
     * @Delete("api/patients/{patient_unique_id}/procedures/{patient_procedure_id}")
     * @param $patient_procedure_id
     * @param $patient_id
     * @return array|JsonResponse
     * @View
     */
    public function deletePatientProcedureAction($patient_unique_id, $patient_procedure_id )
    {
        //Make sure the patient procedure exists
        $patient_procedure = $this->getDoctrine()->getRepository("AppBundle:PatientProcedure")->findOneBy(
            array("id" => $patient_procedure_id)
        );
        if(!$patient_procedure) {
            return new JsonResponse([
                "message" => "nothing_found", "message_description" => "Procedure not Found"
            ], 404);
        }
        try {

            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($patient_procedure);
            $em->flush();

            //Delete from patient billing details
            $this->deletePatientBillingDetailWhenPatientProcedure($patient_procedure_id);
            return array("action" => "deleted");

        }catch (\Exception $e){
            return new JsonResponse([
                'error' => 'exception_error', 'error_description' => $e->getMessage()
            ], 500);
        }

    }


    private function deletePatientBillingDetailWhenPatientProcedure($patient_procedure_id){
        //Find patient procedure
        $patient_billing_detail = $this->getDoctrine()->getRepository("AppBundle:PatientBillingDetail")->findOneBy(
            array("patientProcedureId" => $patient_procedure_id)
        );
        if($patient_billing_detail) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($patient_billing_detail);
            $em->flush();
            return true;
        }
        return false;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Patient Billing


    /**
     * @Get("api/patients/{patient_uniqueid}/billing-detail")
     * @param $patient_uniqueid
     * @View
     */
    public function returnPatientBillingDetailsAction($patient_uniqueid)
    {

        $patient_billing_detail =  $this->getDoctrine()
            ->getRepository("AppBundle:PatientBillingDetail")
            ->createQueryBuilder('pbl')
            ->where('pbl.billed != :nullp')
            ->orWhere('pbl.received != :nullp')
            ->orderBy( 'pbl.lastModified', 'desc' )
            ->setParameter('nullp', 'null')
            ->getQuery()
            ->getResult();
        if($patient_billing_detail){
            return $patient_billing_detail;
        }
        return new JsonResponse([
            "message" => "nothing_found", "message_description" => "Nothing Found"
        ], 204);
    }

    /**
     * @Get("api/patients/{patient_uniqueid}/billing-detail/due-balance")
     * @param $patient_uniqueid
     * @View
     */
    public function returnPatientDueBalanceAction($patient_uniqueid)
    {
        $total_received = 0;
        $total_billed = 0;
        //Get total billed
        $patient_billing_detail_billed =  $this->getDoctrine()
            ->getRepository("AppBundle:PatientBillingDetail")
            ->createQueryBuilder('pbl')
            ->select('pbl.billed')
            ->where('pbl.billed != 0')
            ->getQuery()
            ->getArrayResult();

        if($patient_billing_detail_billed)$total_billed = array_sum(array_map('current', $patient_billing_detail_billed));

        //Get total received
        $patient_billing_detail_received =  $this->getDoctrine()
            ->getRepository("AppBundle:PatientBillingDetail")
            ->createQueryBuilder('pbl')
            ->select('pbl.received')
            ->where('pbl.received > 0')
            ->getQuery()
            ->getArrayResult();
        if($patient_billing_detail_received)$total_received = array_sum(array_map('current', $patient_billing_detail_received));

        //Calculate due balance
        $due_balance = $total_billed - $total_received;
        return $due_balance;
    }


    /**
     * @Post("api/patients/{patientUniqueid}/billing-detail")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function newPatientBillingDetailAction($patientUniqueid, Request $request)
    {
        $req_data = json_decode($request->getContent(), true);
        $new_patient_billing_detail_data = $req_data['payment'];

        try {
            $new_patient_billing_record = $this->addNewPatientBillingDetail($new_patient_billing_detail_data);
            return $new_patient_billing_record;
        }catch (\Exception $e){
            return new JsonResponse([
                'error' => 'exception_error', 'error_description' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * @Post("api/patients/sms/{cellphone}")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function sendSMSAction($cellphone, Request $request)
    {


    }


}
