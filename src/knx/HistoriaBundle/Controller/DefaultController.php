<?php

namespace knx\HistoriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function InicioAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("historia_index"));
        
        return $this->render('HistoriaBundle:Default:inicio.html.twig');
    }
}