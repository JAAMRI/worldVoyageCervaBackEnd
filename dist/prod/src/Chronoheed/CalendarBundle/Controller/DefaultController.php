<?php

namespace Chronoheed\CalendarBundle\Controller;

use AppBundle\Entity\Encounter;
use AppBundle\Entity\EncounterProcedure;
use AppBundle\Entity\Event;
use AppBundle\Entity\Patient;
use AppBundle\Form\EventType;
use AppBundle\Form\PatientType;
use Doctrine\DBAL\Driver\PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DefaultController extends Controller
{
    /**
     * @Post("api/events")
     * @param Request $request
     * @View
     */
    public function newEventAction(Request $request)
    {
        $req_data = json_decode($request->getContent(), true);
        $event_data = $req_data['event'];
        //Implode procedures id to convert them to string
        $event_data['procedures'] = implode("|", $event_data['procedures']);
//        if(!isset($event_data['start']) || !isset($event_data['end']) || !isset($event_data['type']) || !isset($event_data['provider']) || (!isset($event_data['lastname']) && !isset($event_data['patient'])) )
        if(!isset($event_data['start']) || !isset($event_data['end']) || !isset($event_data['type']) || !isset($event_data['provider']) )
        {
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => "Form errors"
            ], 400);
        }
        //Data received is good, proceed
        try {
            //Validate data and insert new User if data is valid
            $event = new Event();
            $form = $this->createForm(new EventType(), $event);

            $form->submit($event_data);

            if ($form->isValid()) {
                try {
                    $em = $this->getDoctrine()->getEntityManager();
                    $provider = $em->find("AppBundle:User", $event_data['provider']);
                    $event->setProvider( $provider );
                    //Set Event status to CONFIRMED as it's internally tken
                    $event->setStatus("CONFIRMED");
                    //Set event color
                    if( !$event_data['color'] ){
                        $event->setColor($provider->getColor());
                    }
                    $patientName = "";
                    if(isset($event_data['lastname'])){
                        $patientName = $event_data['lastname'];
                    }
                    //GET PATIENT if patient is ESTABLISHED_PATIENT
                    if( isset($event_data['patient']) ){
                        $patient = $em->find("AppBundle:Patient", $event_data['patient']);
                        if( $patient ){
                            $event->setPatient( $patient );
                            $lastname = $patient->getLastname();
                            $event->setLastname( $lastname );
                            $patientName = $patient->getLastname() . " " . $patient->getFirstname() . " [" . $patient->getId() . "]";
                        }
                    }
                    //Set type
                    $type = "";
                    if( isset($event_data['type']) ){
                        $type = $event_data['type'];
                    }
                    //Set title
                    $event->setTitle($patientName . " - " . $provider->getFirstname() . " " . $provider->getLastname() );

                    //Insert new event
                    $em->persist($event);
                    $em->flush();
                    return $event;
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

    private function getUserById($id){
        $em = $this->getDoctrine()->getManager();
        return $em->find("AppBundle:User", $id);
    }

    private function getPatientById($id){
        $em = $this->getDoctrine()->getManager();
        return $em->find("AppBundle:Patient", $id);
    }

    /**
     * @Get("api/events")
     * @param Request $request
     * @View
     */
    public function returnEventsAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        //Check if provider Id exists
        $provider = null;
        if( $request->query->get('pId') > 0 ){
            $provider = $this->getDoctrine()->getManager()->find("AppBundle:User", $request->query->get('pId') );
        }
        return $this->getDoctrine()->getRepository("AppBundle:Event")->returnEvents($start, $end, $provider);
    }

    /**
     * @Put("api/events/{eventId}")
     * @param Request $request
     * @param $eventId
     * @View
     */
    public function updateEventAction(Request $request, $eventId)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->find("AppBundle:Event", $eventId);
        if($event){
            if($event->getStatus() == "CHECKED_IN"){
                return new JsonResponse([
                    'error' => 'not_acceptable', 'error_description' => 'This event can not be updated, already checked in'
                ], 406);
            }

            $req_data = json_decode($request->getContent(), true);
            $event_data = $req_data['event'];
            //Implode procedures id to convert them to string
            if(isset($event_data['procedures'])) $event_data['procedures'] = implode("|", $event_data['procedures']);
            $event_data['type'] = $event->getType();
            $event_data['color'] = $event->getColor();
            $event_data['title'] = $event->getTitle();
            $event_data['lastname'] = $event->getLastname();

            //Validate data and insert new User if data is valid
            $form = $this->createForm(new EventType(), $event);
            $form->submit($event_data);
            if ($form->isValid()) {
                //Update values
                try {
                    //Insert new event
                    $em->persist($event);
                    $em->flush();
                    return $event;
                }catch (PDOException $e){
                    return new JsonResponse([
                        'error' => 'bad_request', 'error_description' => $e->getMessage()
                    ], $e->getCode());
                }
            }
        }
        return new JsonResponse([
            'error' => 'bad_request', 'error_description' => 'resource not found'
        ], 404);
    }

    /**
     * @Delete("api/events/{eventId}")
     * @param $eventId
     * @View
     */
    public function deleteEventAction($eventId)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->find("AppBundle:Event", $eventId);
        if($event->getStatus() == "CHECKED_IN"){
            return new JsonResponse([
                'error' => 'not_acceptable', 'error_description' => 'This event can not be deleted, already checked in'
            ], 406);
        }
        if($event){
            $em->remove($event);
            $em->flush();
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'resource not found'
            ], 204);
        }
        return new JsonResponse([
            'error' => 'bad_request', 'error_description' => 'resource not found'
        ], 404);
    }

    /**
     * @Get("api/appointments")
     * @param Request $request
     * @View
     */
    public function returnAppointmentsAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $providerId = $request->query->get('pId');

        $appointments = $this->getDoctrine()->getRepository("AppBundle:Event")->returnAppointments($start, $end, $providerId);

        if($appointments){
            return $this->formatTodaysAppointmentsForDataTable($appointments);
        }
        return array('data' => "");
    }

    private function formatTodaysAppointmentsForDataTable ($results){
        $array_ = [];
        foreach ($results as $row){
            $rowArr = [];
            //Set appointment id as row id
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
            if( $row->getPhone() ){ $phone = $row->getPhone();}
            else if($row->getPatient()) {
                if ($row->getPatient()->getCellphone()) {
                    $phone = $row->getPatient()->getCellphone();
                } else if ($row->getPatient()->getOfficephone()) {
                    $phone = $row->getPatient()->getOfficephone();
                } else if ($row->getPatient()->getHomephone()) {
                    $phone = $row->getPatient()->getHomephone();
                }
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
            //Appointment field
            $rowArr["appointment"] = $row->getStart();
            //Set ProviderFullName
            $rowArr["providerFullName"] = $row->getProvider()->getFirstname() . " " . $row->getProvider()->getLastname();
            $rowArr["status"] = $row->getStatus();
            array_push($array_, $rowArr);
        }
        return array("recordsTotal" => count($results), "data" => $array_);
    }


    /**
     * @Get("api/events/{eventId}/checkin")
     * @param Request $request
     * @View
     */
    public function eventsCheckInAction($eventId)
    {
        try {
            //MAKE SURE THE EVENT EXISTS
            $em = $this->getDoctrine()->getManager();
//            $encounter = $em->find("AppBundle:Encounter", 40);
            if(!$event = $em->find("AppBundle:Event", $eventId))
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => 'Event does not exist '
                ], 400);

            //MAKE SURE THE EVENT IS NOT ALREADY CHECKED_IN
            if ($event->getStatus() == "CHECKED_IN")//Check if event is already checked in => return error
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => 'Event already checked '
                ], 400);

            //CHECK WETHER THIS IS AN ESTABLISHED OR OLD PATIENT
            if (! $patient = $event->getPatient()) {//this is null => this is a new patient
                $patient_data = array();
                $patient_data['lastname'] = $event->getLastname();
                $patient_data['rating'] = 5;
                $patient_data['cellphone'] = $event->getPhone();
                if ($patient = $this->createPatient($patient_data)) {//Patient created
                    //Update this appointment with the actual created patient
                    $event->setPatient($patient);
                    //Update event status to "CHECKED_IN" | TO BE FIXED LATER, WE'RE NOT SURE THAT EVERYTHING WILL GO FINE AT THIS POINT
                    $em->persist($event);
                    $em->flush();
                } else {
                    //Error creating this patient
                    return new JsonResponse([
                        'error' => 'bad_request', 'error_description' => 'we could not create this patient'
                    ], 400);
                }
            }
            //At this stage the event has an established patient | create encounter
            if(! $newEncounter = $this->createEncounterFromEvent($event) )
                throw new \Exception("Problem creating the new encounter");

            //Add procedures to encounter
            if ( ! $res = $this->addProceduresToEncounterFromEvent($newEncounter, $event->getProcedures()) )//Check if an error happened in creating
            {
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => 'Something went wrong in creating encounter procedures' . $res
                ], 400);
            }
            //everything went well | update actual event |
            $event->setStatus("CHECKED_IN");
            $em->persist($event);
            $em->flush();
            //Notify node with the new event status
            $this->get("chronoheed.notify")->nodePushViaGet("event=patientCheckin&patientId=" . $patient->getId());
            //return success to client true
            return array("status" => "CHECKED_IN");
        }catch (\Exception $e){
            return new JsonResponse([
                'error' => 'unknown error', 'error_description' => $e->getMessage()
            ], 500);
        }
    }

    private function addProceduresToEncounterFromEvent(Encounter $encounter, $procedureIdsList){
        try{
            $procedureIdsArr = explode("|", $procedureIdsList);
            $em = $this->getDoctrine()->getManager();
            foreach($procedureIdsArr as $procedureId){
                //Get default Procedure
                if ($defaultProcedure = $em->find("AppBundle:DefaultProcedure", $procedureId) ) {//make sure default procedures is found
                    //Set proper values for encounter procedure
                    $encounterProcedure = new EncounterProcedure();
                    $encounterProcedure->setProvider($encounter->getProvider());
                    $encounterProcedure->setCode($defaultProcedure->getCode());
                    $encounterProcedure->setCost($defaultProcedure->getCost());
                    $encounterProcedure->setLabel($defaultProcedure->getLabel());
                    $encounterProcedure->setEncounter($encounter);
                    $em->persist($encounterProcedure);
                    $em->flush();
                }//End if
            }
            //Everything went good
            return true;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    private function createEncounterFromEvent(Event $event){
        try {
            $encounter = new Encounter();
            $encounter->setProvider($event->getProvider());
            $encounter->setPatient($event->getPatient());
            $encounter->setCheckinTime(new \DateTime());
            $encounter->setStatus("CHECKED_IN");
            $encounter->setEvent($event);
            $em = $this->getDoctrine()->getManager();
            $em->persist($encounter);
            $em->flush();
            return $encounter;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    private function createPatient($patient_data){
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
                    //Insert new patient
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($patient);
                    $em->flush();
                    return $patient;
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


    /**
     * @Get("api/events/{event_id}/cloneafter/{clone_number_of_days}")
     * @param  $event_id
     * @param  $clone_number_of_days
     * @View
     */
    public function cloneEventAction($event_id, $clone_number_of_days)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$event = $em->find("AppBundle:Event", $event_id))
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'Event does not exist '
            ], 400);
        //Make sure $clone_number_of_days is an integer
        $clone_number_of_days = intval($clone_number_of_days);
        if( !is_numeric( $clone_number_of_days) || $clone_number_of_days < 1 )
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => 'Number of days is wrong ' . $clone_number_of_days
            ], 400);

        $title = $event->getTitle();
        $start = $event->getStart();
        $end = $event->getEnd();
        $color = $event->getColor();
        $lastname = $event->getLastname();
        $phone = $event->getPhone();
        $type = $event->getType();
        $provider = $event->getProvider();
        $patient = $event->getPatient();
        $procedures = $event->getProcedures();
        $status = 'CONFIRMED';//This is a new event
        //Adjust start and end
        $newStart = $start->modify('+' . $clone_number_of_days . ' days');
        $newEnd = $end->modify('+' . $clone_number_of_days . ' days');
        $newEvent = new Event();
        $newEvent->setStart($newStart);
        $newEvent->setEnd($newEnd);
        $newEvent->setTitle($title);
        $newEvent->setColor($color);
        $newEvent->setLastname($lastname);
        $newEvent->setPhone($phone);
        $newEvent->setType($type);
        if($event->getPatient()) $newEvent->setPatient($patient);
        if($event->getProvider()) $newEvent->setProvider($provider);
        $newEvent->setProcedures($procedures);
        $newEvent->setStatus($status);
        $em->persist($newEvent);
        $em->flush();
        return $newEvent;
    }


    /**
     * @Get("api/notifications/events")
     */
    public function notificationsAction() {
//        $response = new StreamedResponse(function() {
//        $response = new StreamedResponse(function() {
//                while(true) {
//                $message = "";
//
//                $messagesNotification = $this->get('session')->getFlashBag()->get('message_notification');; // code that search notifications from session
//                if(count($messagesNotification)>0){
//                    foreach ( $messagesNotification as $messageNotification ) {
//                        $message .= "data: " . messageNotification  . PHP_EOL;
//                    }
//                    $message .= PHP_EOL;
//                    echo $message;
//                    ob_flush();
//                    flush();
//
//                }
//                sleep(8);
//            };
//
//        });
//
//        $response->headers->set('Content-Type', 'text/event-stream');
//        $response->headers->set('Cache-Control', 'no-cache');
//        return $response;
//
//
//        $response = new StreamedResponse();
//        $response->setCallback(function () {
//            var_dump('Hello World');
//            flush();
//            sleep(20);
//            var_dump('Hello World');
//            flush();
//        });
//        $response->send();


    }


}
