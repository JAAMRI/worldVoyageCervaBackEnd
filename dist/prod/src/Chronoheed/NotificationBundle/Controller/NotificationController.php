<?php

namespace Chronoheed\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NotificationController extends Controller
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function nodePushViaGet( $parameters = "")
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->container->getParameter('node_server_host') . ":" . $this->container->getParameter('node_server_port') . "/push?" . $parameters,
            CURLOPT_USERAGENT => 'NODE PUSH CURL REQUEST'
        ));
        // Send the request & save response to $resp
        curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
    }

}
