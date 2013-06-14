<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FarmaciaBundle:Default:index.html.twig', array('name' => $name));
    }
}
