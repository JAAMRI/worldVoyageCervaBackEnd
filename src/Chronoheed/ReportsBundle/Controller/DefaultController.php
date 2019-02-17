<?php

namespace Chronoheed\ReportsBundle\Controller;

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
     * @Get("api/reports/payments")
     * @param Request $request
     * @return array|JsonResponse
     * @View
     */
    public function returnReportPaymentsAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $method = $request->query->get('method');

        if(isset($method)){
            if($method == "" or $method == "null"){
                $method = null;
            }
        }

        $payments = $this->getDoctrine()->getRepository("AppBundle:Payment")->returnPaymentsByDateAndOrMethod($start, $end, $method);

        if($payments){
            return $this->formatReportPaymentsForDataTable($payments);
        }
        return array('data' => "");
    }

    public function formatReportPaymentsForDataTable($results){
        $array_ = [];
        $recordsTotal = 0;
        foreach ($results as $payment){
            //Put payments in an array
            $rowArr = [];
            $rowArr['amount'] = $payment['amount'];
            $rowArr['paymentMethod'] = $payment['paymentMethod'];
            $rowArr['transactionDate'] = $payment['transactionDate'];
            $notes = "N/A";
            if($payment['notes'] != null && $payment['notes'] != ""){$notes = $payment['notes'];}
            $rowArr['notes'] = $notes;

            $paymentDate = $rowArr['transactionDate'];
            if($payment['paymentDate'] != null && $payment['paymentDate'] != ""){$paymentDate = $payment['paymentDate'];}
            $rowArr['paymentDate'] = $paymentDate;


            $paymentNumber = "N/A";
            if($payment['paymentNumber'] != null && $payment['paymentNumber'] != ""){$paymentNumber = $payment['paymentNumber'];}
            $rowArr['paymentNumber'] = $paymentNumber;

            $recordsTotal++;
            array_push($array_, $rowArr);
        }
        return array("recordsTotal" => $recordsTotal, "data" => $array_);
    }


}
