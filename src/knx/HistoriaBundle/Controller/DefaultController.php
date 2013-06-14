<?php

namespace knx\HistoriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HistoriaBundle:Default:index.html.twig', array('name' => $name));
    }
}
