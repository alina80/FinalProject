<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class OrderController extends Controller
{
    /**
     * @Route("/addClient/{client_id}")
     */
    public function addClientAction($client_id)
    {
        return $this->render('AppBundle:Order:add_client.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/previewOrder")
     */
    public function previewOrderAction()
    {
        return $this->render('AppBundle:Order:preview_order.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/createOrder")
     */
    public function createOrderAction()
    {
        return $this->render('AppBundle:Order:create_order.html.twig', array(
            // ...
        ));
    }

}
