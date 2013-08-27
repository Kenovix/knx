<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\Servicio;
use knx\ParametrizarBundle\Form\ServicioType;

class ServicioController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Centro de costo", $this->get("router")->generate("servicio_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $servicio = $em->getRepository('ParametrizarBundle:Servicio')->findAll();
        
        return $this->render('ParametrizarBundle:Servicio:list.html.twig', array(
                'servicios'  => $servicio
        ));
    }

    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Centro de costo", $this->get("router")->generate("servicio_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$servicio = new Servicio();
    	$form   = $this->createForm(new ServicioType(), $servicio);
    	
    	$em = $this->getDoctrine()->getEntityManager();

    	return $this->render('ParametrizarBundle:Servicio:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Centro de costo", $this->get("router")->generate("servicio_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$request = $this->getRequest();
    	
    	$servicio = new Servicio();    	
    	$form = $this->createForm(new ServicioType(), $servicio);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {	    		
	    		 
	    		$em = $this->getDoctrine()->getEntityManager();
	    		
	    		$em->persist($servicio);
	    		$em->flush();
	    
	    		$this->get('session')->setFlash('ok', 'El servicio ha sido creado éxitosamente.');
	    
	    		return $this->redirect($this->generateUrl('servicio_show', array("servicio" => $servicio->getId())));
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:Servicio:new.html.twig', array(
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($servicio)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$servicio = $em->getRepository('ParametrizarBundle:Servicio')->find($servicio);
    	
    	if (!$servicio) {
    		throw $this->createNotFoundException('El servicio solicitado no esta disponible.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Centro de costo", $this->get("router")->generate("servicio_list"));
    	$breadcrumbs->addItem($servicio->getNombre());
    	
    	return $this->render('ParametrizarBundle:Servicio:show.html.twig', array(
    			'servicio'  => $servicio
    	));
    }
    
    public function editAction($servicio)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$servicio = $em->getRepository('ParametrizarBundle:Servicio')->find($servicio);
    
    	if (!$servicio) {
    		throw $this->createNotFoundException('El servicio solicitado no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Centro de costo", $this->get("router")->generate("servicio_list"));
    	$breadcrumbs->addItem($servicio->getNombre(), $this->get("router")->generate("servicio_show", array("servicio" => $servicio->getId())));
    	$breadcrumbs->addItem("Modificar ".$servicio->getNombre());
    
    	$form = $this->createForm(new ServicioType(), $servicio);
    
    	return $this->render('ParametrizarBundle:Servicio:edit.html.twig', array(
    			'servicio' => $servicio,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($servicio)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$servicio = $em->getRepository('ParametrizarBundle:Servicio')->find($servicio);
    
    	if (!$servicio) {
    		throw $this->createNotFoundException('La servicio solicitada no existe.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Centro de costo", $this->get("router")->generate("servicio_list"));
    	$breadcrumbs->addItem($servicio->getNombre(), $this->get("router")->generate("servicio_show", array("servicio" => $servicio->getId())));
    	$breadcrumbs->addItem("Modificar ".$servicio->getNombre());
    
    	$form = $this->createForm(new ServicioType(), $servicio);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($servicio);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La servicio ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('servicio_edit', array('servicio' => $servicio->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:Servicio:edit.html.twig', array(
    			'servicio' => $servicio,
    			'form' => $form->createView()
    	));
    }
}