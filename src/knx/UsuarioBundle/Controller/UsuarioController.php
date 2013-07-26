<?php

namespace knx\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\UsuarioBundle\Entity\Usuario;
use knx\UsuarioBundle\Form\UsuarioBasicType;
use knx\UsuarioBundle\Form\UsuarioType;


class UsuarioController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Usuario", $this->get("router")->generate("usuario_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $usuario = $em->getRepository('UsuarioBundle:Usuario')->findAll();
        
        return $this->render('UsuarioBundle:Usuario:list.html.twig', array(
                'usuarios'  => $usuario
        ));
    }
}