<?php

namespace knx\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\UsuarioBundle\Entity\Usuario;
use knx\UsuarioBundle\Form\UsuarioType;

class UsuarioController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Almacen", $this->get("router")->generate("almacen_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $almacen = $em->getRepository('ParametrizarBundle:Almacen')->findAll();
        
        return $this->render('ParametrizarBundle:Almacen:list.html.twig', array(
                'almacenes'  => $almacen
        ));
    }
    
    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Almacen", $this->get("router")->generate("almacen_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$usuario = new Usuario();
    	
    	$form = $this->createForm(new UsuarioType('mi_user_registration'), $usuario);

    	return $this->render('UsuarioBundle:Usuario:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Almacen", $this->get("router")->generate("almacen_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$request = $this->getRequest();
    	
    	$almacen = new Almacen();    	
    	$form = $this->createForm(new AlmacenType(), $almacen);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {	    		
	    		 
	    		$em = $this->getDoctrine()->getEntityManager();
	    		
	    		$em->persist($almacen);
	    		$em->flush();
	    
	    		$this->get('session')->setFlash('ok', 'El almacen ha sido creada éxitosamente.');
	    
	    		return $this->redirect($this->generateUrl('almacen_show', array("almacen" => $almacen->getId())));
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:Almacen:new.html.twig', array(
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($almacen)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$almacen = $em->getRepository('ParametrizarBundle:Almacen')->find($almacen);
    	
    	if (!$almacen) {
    		throw $this->createNotFoundException('El almacen solicitada no esta disponible.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Almacen", $this->get("router")->generate("almacen_list"));
    	$breadcrumbs->addItem($almacen->getNombre());
    	
    	return $this->render('ParametrizarBundle:Almacen:show.html.twig', array(
    			'almacen'  => $almacen
    	));
    }
    
    public function editAction($almacen)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$almacen = $em->getRepository('ParametrizarBundle:Almacen')->find($almacen);
    
    	if (!$almacen) {
    		throw $this->createNotFoundException('El almacen solicitado no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Almacen", $this->get("router")->generate("almacen_list"));
    	$breadcrumbs->addItem($almacen->getNombre(), $this->get("router")->generate("almacen_show", array("almacen" => $almacen->getId())));
    	$breadcrumbs->addItem("Modificar ".$almacen->getNombre());
    
    	$form = $this->createForm(new AlmacenType(), $almacen);
    
    	return $this->render('ParametrizarBundle:Almacen:edit.html.twig', array(
    			'almacen' => $almacen,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($almacen)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$almacen = $em->getRepository('ParametrizarBundle:Almacen')->find($almacen);
    
    	if (!$almacen) {
    		throw $this->createNotFoundException('La almacen solicitada no existe.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Almacen", $this->get("router")->generate("almacen_list"));
    	$breadcrumbs->addItem($almacen->getNombre(), $this->get("router")->generate("almacen_show", array("almacen" => $almacen->getId())));
    	$breadcrumbs->addItem("Modificar ".$almacen->getNombre());
    
    	$form = $this->createForm(new AlmacenType(), $almacen);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($almacen);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'El almacen ha sido modificado éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('almacen_edit', array('almacen' => $almacen->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:Almacen:edit.html.twig', array(
    			'almacen' => $almacen,
    			'form' => $form->createView()
    	));
    }
}