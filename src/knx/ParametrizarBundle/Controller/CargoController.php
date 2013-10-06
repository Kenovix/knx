<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\Cargo;
use knx\ParametrizarBundle\Form\CargoType;

class CargoController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cargo", $this->get("router")->generate("cargo_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        
    	$dql = $em->createQuery( "SELECT
										c
									 FROM
										ParametrizarBundle:Cargo c
									 ORDER BY
										c.nombre ASC");
    	
    	$cargo = $dql->getResult();
    	
    	
        
        return $this->render('ParametrizarBundle:Cargo:list.html.twig', array(
                'cargos'  => $cargo
        ));
    }
    
    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cargo", $this->get("router")->generate("cargo_list"));
    	$breadcrumbs->addItem("Nueva");
    	
    	$cargo = new Cargo();
    	$form   = $this->createForm(new CargoType(), $cargo);
    	
    	$em = $this->getDoctrine()->getEntityManager();

    	return $this->render('ParametrizarBundle:Cargo:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cargo", $this->get("router")->generate("cargo_list"));
    	$breadcrumbs->addItem("Nueva");
    	
    	$request = $this->getRequest();
    	
    	$cargo = new Cargo();    	
    	$form = $this->createForm(new CargoType(), $cargo);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {	    		
	    		 
	    		$em = $this->getDoctrine()->getEntityManager();
	    		
	    		$em->persist($cargo);
	    		$em->flush();
	    
	    		$this->get('session')->setFlash('ok', 'La cargo ha sido creada éxitosamente.');
	    
	    		return $this->redirect($this->generateUrl('cargo_show', array("cargo" => $cargo->getId())));
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:Cargo:new.html.twig', array(
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($cargo)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$cargo = $em->getRepository('ParametrizarBundle:Cargo')->find($cargo);
    	
    	if (!$cargo) {
    		throw $this->createNotFoundException('La cargo solicitada no esta disponible.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cargo", $this->get("router")->generate("cargo_list"));
    	$breadcrumbs->addItem($cargo->getNombre());
    	
    	return $this->render('ParametrizarBundle:Cargo:show.html.twig', array(
    			'cargo'  => $cargo
    	));
    }
    
    public function editAction($cargo)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$cargo = $em->getRepository('ParametrizarBundle:Cargo')->find($cargo);
    
    	if (!$cargo) {
    		throw $this->createNotFoundException('La cargo solicitada no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cargo", $this->get("router")->generate("cargo_list"));
    	$breadcrumbs->addItem($cargo->getNombre(), $this->get("router")->generate("cargo_show", array("cargo" => $cargo->getId())));
    	$breadcrumbs->addItem("Modificar ".$cargo->getNombre());
    
    	$form = $this->createForm(new CargoType(), $cargo);
    
    	return $this->render('ParametrizarBundle:Cargo:edit.html.twig', array(
    			'cargo' => $cargo,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($cargo)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$cargo = $em->getRepository('ParametrizarBundle:Cargo')->find($cargo);
    
    	if (!$cargo) {
    		throw $this->createNotFoundException('La cargo solicitada no existe.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cargo", $this->get("router")->generate("cargo_list"));
    	$breadcrumbs->addItem($cargo->getNombre(), $this->get("router")->generate("cargo_show", array("cargo" => $cargo->getId())));
    	$breadcrumbs->addItem("Modificar ".$cargo->getNombre());
    
    	$form = $this->createForm(new CargoType(), $cargo);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($cargo);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La cargo ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('cargo_edit', array('cargo' => $cargo->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:Cargo:edit.html.twig', array(
    			'cargo' => $cargo,
    			'form' => $form->createView()
    	));
    }
}