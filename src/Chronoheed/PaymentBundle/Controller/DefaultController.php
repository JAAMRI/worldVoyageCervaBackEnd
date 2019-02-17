<?php

namespace Chronoheed\PaymentBundle\Controller;

use AppBundle\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Get("api/patients/{patient_id}/procedures/done")
     * @param patient_id
     *
     * @return array|JsonResponse
     * @View
     */
    public function getAllPatientProceduresDoneAction($patient_id)
    {
        $em = $this->getDoctrine()->getManager();
        $patient = $em->find("AppBundle:Patient", $patient_id);

        $patientProceduresDone = $this->getDoctrine()->getRepository("AppBundle:Encounter")->returnEncountersPerPatient($patient);

        if($patientProceduresDone){
            return $this->formatPatientEncounterProceduresDoneForDataTable($patientProceduresDone);
        }
        return array('data' => "");
    }

    public function formatPatientEncounterProceduresDoneForDataTable($results){
        $array_ = [];
        $recordsTotal = 0;
        foreach ($results as $encounter){
            //Put procedures in an array
            if($procedures = $encounter->getProcedures()) {
                foreach ($procedures as $procedure) {
                    if ($procedure->getStatus() == "DONE") {
                        $rowArr = [];
                        $rowArr['procedure'] = $procedure->getLabel();
                        $rowArr['provider'] = $procedure->getProvider()->getFirstname() . " " . $procedure->getProvider()->getLastname();
                        $rowArr['status'] = $procedure->getStatus();
                        $rowArr['checkinTime'] = $encounter->getCheckinTime();
                        $rowArr['cost'] = $procedure->getCost();
                        $recordsTotal++;
                        array_push($array_, $rowArr);
                    }
                }
            }//End if
        }
        return array("recordsTotal" => $recordsTotal, "data" => $array_);
    }

    /**
     * @Get("api/patients/{patient_id}/totalbilled")
     * @param patient_id
     *
     * @return array|JsonResponse
     * @View
     */
    public function getPatientTotalBilledAction($patient_id)
    {
        $em = $this->getDoctrine()->getManager();
        $patient = $em->find("AppBundle:Patient", $patient_id);

        $patientProceduresDone = $this->getDoctrine()->getRepository("AppBundle:Encounter")->returnEncountersPerPatient($patient);

        if($patientProceduresDone){
            return $this->calculatePatientTotalBilled($patientProceduresDone);
        }
        return array('data' => "");
    }

    private function calculatePatientTotalBilled($results){
        $total_billed = 0;
        foreach ($results as $encounter){
            //Put procedures in an array
            if($procedures = $encounter->getProcedures()) {
                foreach ($procedures as $procedure) {
                    if ($procedure->getStatus() == "DONE") {
                        $total_billed += $procedure->getCost();
                    }
                }
            }//End if
        }
        return $total_billed;
    }



    /**
     * @Post("api/patients/{patient_id}/payments")
     * @param Request $request
     * @param $patient_id
     * @return array|JsonResponse
     * @View
     */
    public function newPatientPaymentAction(Request $request, $patient_id)
    {
        try {
            $req_data = json_decode($request->getContent(), true);
            $payment_data = $req_data['newPayment'];

            $em = $this->getDoctrine()->getManager();

            //Check if patient exists
            if (!$patient = $em->find("AppBundle:Patient", $patient_id)) {
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => "Patient does not exist"
                ], 404);
            }


            $payment = new Payment();
            $payment->setPatient($patient);
            $payment->setAmount($payment_data['amount']);
            $payment->setPaymentMethod($payment_data['paymentMethod']);
            $payment->setTransactionDate(new \DateTime());

            if(isset($payment_data['paymentNumber'])){ $payment->setPaymentNumber( $payment_data['paymentNumber'] ); }

            if(isset($payment_data['paymentDate'])){
                $payment->setPaymentDate(   date_create_from_format('Y-m-d', $payment_data['paymentDate']) );
            }//End if

            if(isset($payment_data['notes'])){ $payment->setNotes($payment_data['notes']); }

            $em->persist($payment);
            $em->flush();
            return array("success" => true);
        }catch (\Exception $e){
                return new JsonResponse([
                    'error' => 'bad_request', 'error_description' => $e->getMessage()
                ], 500);

        }
    }


    /**
     * @Get("api/patients/{patient_id}/totalpaid")
     * @param patient_id
     *
     * @return array|JsonResponse
     * @View
     */
    public function getPatientTotalPaidAction($patient_id)
    {
        $em = $this->getDoctrine()->getManager();
        //Check if patient exists
        if (!$patient = $em->find("AppBundle:Patient", $patient_id)) {
            return new JsonResponse([
                'error' => 'bad_request', 'error_description' => "Patient does not exist"
            ], 404);
        }

        $payments = $this->getDoctrine()->getRepository("AppBundle:Payment")->returnPaymentsByPatient($patient);

        if($payments){
            return $this->calculatePatientTotalPaid($payments);
        }
        return 0;
    }

    private function calculatePatientTotalPaid($results){
        $total_paid = 0;
        foreach ($results as $payment){
                        $total_paid += $payment->getAmount();
        }
        return $total_paid;
    }

    /**
     * @Get("api/patients/{patient_id}/payments")
     * @param patient_id
     *
     * @return array|JsonResponse
     * @View
     */
    public function getAllPatientPaymentsAction($patient_id)
    {
        $em = $this->getDoctrine()->getManager();
        $patient = $em->find("AppBundle:Patient", $patient_id);

        $patientPayments = $this->getDoctrine()->getRepository("AppBundle:Payment")->returnPaymentsByPatient($patient);

        if($patientPayments){
            return $this->formatPatientPaymentsHistoryForDataTable($patientPayments);
        }
        return array('data' => "");
    }

    public function formatPatientPaymentsHistoryForDataTable($results){
        $array_ = [];
        $recordsTotal = 0;
        foreach ($results as $payment){
            //Put payments in an array
                $rowArr = [];
                $rowArr['DT_RowID'] = $payment->getId();
                $rowArr['amount'] = $payment->getAmount();
                $rowArr['paymentMethod'] = $payment->getPaymentMethod();
                $rowArr['transactionDate'] = $payment->getTransactionDate();
                $notes = "N/A";
                if($payment->getNotes() != null && $payment->getNotes() != ""){$notes = $payment->getNotes();}
                $rowArr['notes'] = $notes;
                $recordsTotal++;
                array_push($array_, $rowArr);
        }
        return array("recordsTotal" => $recordsTotal, "data" => $array_);
    }




}
