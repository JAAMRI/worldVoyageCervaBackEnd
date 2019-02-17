<?php

namespace Chronoheed\PatientBundle\Controller;

use AppBundle\Entity\EncounterProcedure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;

class EncounterController extends Controller
{

    /**
     * @Get("api/encounters")
     * @param Request $request
     * @View
     */
    public function returnEncountersAction(Request $request)
    {
        $today = $request->query->get('today');

        $encounters = $this->getDoctrine()->getRepository("AppBundle:Encounter")->returnEncounters($today);

        if($encounters){
            return $this->formatTodaysEncountersForDataTable($encounters);
        }
        return array('data' => "");
    }

    private function formatTodaysEncountersForDataTable ($results){
        $array_ = [];
        foreach ($results as $row){
            $rowArr = [];
            //Set appointment id as row id
            $rowArr["DT_RowID"] = $row->getId();

            //Set notes
            $notes = "";
            if( $row->getNotes() ){ $notes = $row->getNotes(); }
            $rowArr['notes'] = $row->getNotes();

            $rowArr["DT_RowID"] = $row->getId();
            //Check if photo exists
            $patientPhoto = "TEMPORARY";
            if($row->getPatient()) {
                if ($row->getPatient()->getPhoto()) {
                    $patientPhoto = $row->getPatient()->getPhoto();
                }
            }
            $rowArr['patientPhoto'] = $patientPhoto;
            //Check phone to be added to patientFullName
            $phone = "...";
                if ($row->getPatient()->getCellphone()) {
                    $phone = $row->getPatient()->getCellphone();
                } else if ($row->getPatient()->getOfficephone()) {
                    $phone = $row->getPatient()->getOfficephone();
                } else if ($row->getPatient()->getHomephone()) {
                    $phone = $row->getPatient()->getHomephone();
                }
            //Patient state (NEW / ESTABLISHED)
            $patientState = "NEW_PATIENT";
            //Patient ID
            $patientId = -1;
            //Check if patient exists already
            if($row->getPatient()) { //ESTABLISHED_PATIENT
                $patientId = $row->getPatient()->getId();
                $patientState = "ESTABLISHED_PATIENT";
                $patientFullName = $row->getPatient()->getFirstname() . " " . $row->getPatient()->getLastname();
            }
            else { $patientFullName = $row->getLastname();}
            //Check rating
            $patientRating = -1;
            if($row->getPatient()){$patientRating = $row->getPatient()->getRating();}
            //Cancatenate patientFullName values for client side split "|"
            $rowArr["patientInfo"] = $patientState . "|||" . $patientFullName . "|||" . $patientId . "|||" . $phone . "|||" . $patientRating;
            //Checked in time field
            $rowArr["checkinTime"] = $row->getCheckinTime();
            //Set ProviderFullName
            $rowArr["providerFullName"] = $row->getProvider()->getFirstname() . " " . $row->getProvider()->getLastname();
            $rowArr["status"] = $row->getStatus();
            array_push($array_, $rowArr);
        }
        return array("recordsTotal" => count($results), "data" => $array_);
    }


    /**
     * @Get("api/encounters/{encounter_id}")
     * @param encounter_id
     * @View
     */
    public function returnEncounterAction($encounter_id)
    {
        $em = $this->getDoctrine()->getManager();

        $encounter = $em->find("AppBundle:Encounter", $encounter_id);
        if(!$encounter){
            //Error creating this patient
            return new JsonResponse([
                'error' => 'encounter_not_found', 'error_description' => 'we could not find this encounter'
            ], 404);
        }
        return $encounter;
    }



    /**
     * @Get("api/encounters/{encounter_id}/procedures")
     * @param encounter_id
     * @View
     */
    public function returnEncounterProceduresForDataTableAction($encounter_id)
    {
        $em = $this->getDoctrine()->getManager();

        $encounter = $em->find("AppBundle:Encounter", $encounter_id);
        if(!$encounter){
            //Error creating this patient
            return new JsonResponse([
                'error' => 'encounter_not_found', 'error_description' => 'we could not find this encounter'
            ], 404);
        }

        $encounter_procedures = $this->getDoctrine()->getRepository("AppBundle:EncounterProcedure")->returnEncounterProcedures($encounter);
        if($encounter_procedures){
            return $this->formatEncounterProceduresForDataTable($encounter_procedures);
        }
        return array('data' => "");
    }


    private function formatEncounterProceduresForDataTable ($results){
        $array_ = [];
        foreach ($results as $row){
            $rowArr = [];
            //Set appointment id as row id
            $rowArr["DT_RowID"] = $row->getId();
            $rowArr['procedure'] = $row->getLabel();
            //Build provider
            $rowArr["provider"] = $row->getProvider()->getFirstname() . " " . $row->getProvider()->getLastname() . "|" . $row->getProvider()->getId();
            //Checked in time field
            $rowArr["cost"] = $row->getCost();
            //Build status if null
            $rowArr["status"] = "UNDONE";
            if($row->getStatus()) $rowArr["status"] = $row->getStatus();
            array_push($array_, $rowArr);
        }
        return array("recordsTotal" => count($results), "data" => $array_);
    }


    /**
     * @Post("api/encounters/{encounter_id}/procedures")
     * @param encounter_id
     * @param Request $request
     * @View
     */
    public function addNewEncounterProcedureAction($encounter_id, Request $request)
    {
        $req_data = json_decode($request->getContent(), true);
        $encounter_procedure_data = $req_data['newEncounterProcedure'];
        $em = $this->getDoctrine()->getEntityManager();
        //Check if encounter exists
        if(!$encounter = $em->find("AppBundle:Encounter", $encounter_procedure_data['encounterId'])){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'We could not find the parent encounter'
            ], 404);
        }
        //Get default procedure
        if(!$defaultProcedure = $em->find("AppBundle:DefaultProcedure", $encounter_procedure_data['procedureId'])){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'We could not find the default procedure'
            ], 404);
        }
        //Get provider
        if(!$provider = $em->find("AppBundle:User", $encounter_procedure_data['providerId'])){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'We could not find the provider'
            ], 404);
        }

        $encounterProcedure = new EncounterProcedure();
        $encounterProcedure->setEncounter($encounter);
        $encounterProcedure->setLabel( $defaultProcedure->getLabel() );
        $encounterProcedure->setCost( $defaultProcedure->getCost() );
        $encounterProcedure->setCode( $defaultProcedure->getCode() );
        $encounterProcedure->setProvider($provider);
        //Add to the database
        $em->persist($encounterProcedure);
        $em->flush();
        return array("status" => "success");

    }

    /**
     * @Put("api/encounters/procedures/{procedure_id}")
     * @param procedure_id
     * @param Request $request
     * @View
     */
    public function updateEncounterProcedureAction($procedure_id, Request $request)
    {
        $req_data = json_decode($request->getContent(), true);
        $encounter_procedure_data = $req_data['encounterProcedure'];
        $em = $this->getDoctrine()->getEntityManager();

        //Prevent a hacking attempt
        if( $procedure_id != $encounter_procedure_data['procedureId'] ){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'Problem matching the posted id with the url id'
            ], 400);
        }

        //Get Encounter procedure
        if(!$encounterProcedure = $em->find("AppBundle:EncounterProcedure", $encounter_procedure_data['procedureId'])){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'We could not find the default procedure'
            ], 404);
        }

        //Get provider
        if(!$provider = $em->find("AppBundle:User", $encounter_procedure_data['providerId'])){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'We could not find the provider'
            ], 404);
        }

        if( $encounter_procedure_data['status'] == "UNDONE"){
            $encounter_procedure_data['status'] = null;
        }
        $encounterProcedure->setStatus( $encounter_procedure_data['status'] );
        $encounterProcedure->setCost( $encounter_procedure_data['cost'] );
        $encounterProcedure->setProvider($provider);
        $em->persist($encounterProcedure);
        $em->flush();
        return array("status" => "success");
        //
    }

    /**
     * @Delete("api/encounters/procedures/{procedure_id}")
     * @param procedure_id
     * @View
     */
    public function deleteEncounterProcedureAction($procedure_id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //Get Encounter procedure
        if(!$encounterProcedure = $em->find("AppBundle:EncounterProcedure", $procedure_id)){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'We could not find the default procedure'
            ], 404);
        }

        $em->remove($encounterProcedure);
        $em->flush();
        return array("status" => "success");

    }

    /**
     * @Put("api/encounters/{encounter_id}/status")
     * @param encounter_id
     * @param Request $request
     * @View
     */
    public function updateEncounterStatusAction($encounter_id, Request $request)
    {
        $encounter_data = json_decode($request->getContent(), true);
//        $encounter_data = $req_data['encounter'];
        $em = $this->getDoctrine()->getEntityManager();

        //Make sure status exists
        if(!isset($encounter_data['status'])){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'Status is not specified'
            ], 404);
        }

        //Get encounter
        if(!$encounter = $em->find("AppBundle:Encounter", $encounter_id)){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'We could not find the encounter'
            ], 404);
        }
        //Set Values
        $encounter->setStatus($encounter_data['status']);

        $em->persist($encounter);
        $em->flush();
        return $encounter_data['status'];
    }

    /**
     * @Put("api/encounters/{encounter_id}/notes")
     * @param encounter_id
     * @param Request $request
     * @View
     */
    public function updateEncounterNotesAction($encounter_id, Request $request)
    {
        $encounter_data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getEntityManager();

        //Make sure notes value exists
        if(!isset($encounter_data['notes'])){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'Notes not specified'
            ], 404);
        }

        //Get encounter
        if(!$encounter = $em->find("AppBundle:Encounter", $encounter_id)){
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'We could not find the encounter'
            ], 404);
        }
        //Set Values
        $encounter->setNotes($encounter_data['notes']);

        $em->persist($encounter);
        $em->flush();
        return array("success" => true);
    }


}
