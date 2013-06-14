<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\Proveedor;
use knx\ParametrizarBundle\Form\ProveedorType;

class ProveedorController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Proveedor", $this->get("router")->generate("proveedor_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $proveedor = $em->getRepository('ParametrizarBundle:Proveedor')->findAll();
        
        return $this->render('ParametrizarBundle:Proveedor:list.html.twig', array(
                'proveedores'  => $proveedor
        ));
    }
    
    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Proveedor", $this->get("router")->generate("proveedor_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$proveedor = new Proveedor();
    	$form   = $this->createForm(new ProveedorType(), $proveedor);
    	
    	$em = $this->getDoctrine()->getEntityManager();

    	return $this->render('ParametrizarBundle:Proveedor:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Proveedor", $this->get("router")->generate("proveedor_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$request = $this->getRequest();
    	
    	$proveedor = new Proveedor();    	
    	$form = $this->createForm(new ProveedorType(), $proveedor);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {	    		
	    		 
	    		$em = $this->getDoctrine()->getEntityManager();
	    		
	    		$em->persist($proveedor);
	    		$em->flush();
	    
	    		$this->get('session')->setFlash('ok', 'El proveedor ha sido creada éxitosamente.');
	    
	    		return $this->redirect($this->generateUrl('proveedor_show', array("proveedor" => $proveedor->getId())));
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:Proveedor:new.html.twig', array(
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($proveedor)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$proveedor = $em->getRepository('ParametrizarBundle:Proveedor')->find($proveedor);
    	
    	if (!$proveedor) {
    		throw $this->createNotFoundException('El proveedor solicitada no esta disponible.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Proveedor", $this->get("router")->generate("proveedor_list"));
    	$breadcrumbs->addItem($proveedor->getNombre());
    	
    	return $this->render('ParametrizarBundle:Proveedor:show.html.twig', array(
    			'proveedor'  => $proveedor
    	));
    }
    
    public function editAction($proveedor)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$proveedor = $em->getRepository('ParametrizarBundle:Proveedor')->find($proveedor);
    
    	if (!$proveedor) {
    		throw $this->createNotFoundException('La proveedor solicitada no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Proveedor", $this->get("router")->generate("proveedor_list"));
    	$breadcrumbs->addItem($proveedor->getNombre(), $this->get("router")->generate("proveedor_show", array("proveedor" => $proveedor->getId())));
    	$breadcrumbs->addItem("Modificar ".$proveedor->getNombre());
    
    	$form = $this->createForm(new ProveedorType(), $proveedor);
    
    	return $this->render('ParametrizarBundle:Proveedor:edit.html.twig', array(
    			'proveedor' => $proveedor,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($proveedor)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$proveedor = $em->getRepository('ParametrizarBundle:Proveedor')->find($proveedor);
    
    	if (!$proveedor) {
    		throw $this->createNotFoundException('La proveedor solicitada no existe.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Proveedor", $this->get("router")->generate("proveedor_list"));
    	$breadcrumbs->addItem($proveedor->getNombre(), $this->get("router")->generate("proveedor_show", array("proveedor" => $proveedor->getId())));
    	$breadcrumbs->addItem("Modificar ".$proveedor->getNombre());
    
    	$form = $this->createForm(new ProveedorType(), $proveedor);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($proveedor);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'El proveedor ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('proveedor_edit', array('proveedor' => $proveedor->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:Proveedor:edit.html.twig', array(
    			'proveedor' => $proveedor,
    			'form' => $form->createView()
    	));
    }
}