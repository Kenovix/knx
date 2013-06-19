<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Devolucion;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Form\DevolucionType;


class DevolucionController extends Controller
{
	public function ListAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devoluciones", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findAll();
        
        return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
                'devolucion'  => $devolucion
        ));
    }
    
    public function NewAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devolucions", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem("Nueva Devolucion");
    	$em = $this->getDoctrine()->getEntityManager();
    	$devolucion = new Devolucion();
    	$devolucion->setFecha(new \datetime('now'));
    	$form   = $this->createForm(new DevolucionType(), $devolucion);

    	return $this->render('FarmaciaBundle:Devolucion:new.html.twig', array(
    			'devolucion'=>$devolucion,
    			'form'   => $form->createView()
    	));
    }
    
    
    public function SaveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem("Nueva Devolucion");
    	 
    	$devolucion = new Devolucion();
    	 
    	$request = $this->getRequest();
    	$form   = $this->createForm(new DevolucionType(), $devolucion);
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($devolucion);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El devolucion ha sido creada éxitosamente.');
    
    			return $this->redirect($this->generateUrl('devolucion_show', array("devolucion" => $devolucion->getId())));	
    		}
    	}
    	 
    	return $this->render('FarmaciaBundle:Devolucion:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }
    
    public function ShowAction($devolucion)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->find($devolucion);
    	
    	
    	 
    	if (!$devolucion) {
    		throw $this->createNotFoundException('El devolucion solicitado no esta disponible.');
    	}
    		   	
    	    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devolucions", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem($devolucion->getId());
    	 
    	return $this->render('FarmaciaBundle:Devolucion:show.html.twig', array(
    			'devolucion'  => $devolucion,
    			
    			
    	));
    }
    
    public function EditAction($devolucion)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->find($devolucion);
    
   	   if (!$devolucion) {
    		throw $this->createNotFoundException('El devolucion solicitado no esta disponible.');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devolucions", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem($devolucion->getId(), $this->get("router")->generate("devolucion_show", array("devolucion" => $devolucion->getId())));
    	$breadcrumbs->addItem("Modificar".$devolucion->getId());
    
    	$form   = $this->createForm(new DevolucionType(), $devolucion);
    
    	return $this->render('FarmaciaBundle:Devolucion:edit.html.twig', array(
    			'devolucion' => $devolucion,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function UpdateAction($devolucion)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->find($devolucion);
    
        if (!$devolucion) {
    		throw $this->createNotFoundException('El devolucion solicitado no esta disponible.');
    	}
    
    	$form = $this->createForm(new DevolucionType(), $devolucion);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($devolucion);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El devolucion ha sido modificado éxitosamente.');
    
    			return $this->redirect($this->generateUrl('devolucion_show', array("devolucion" => $devolucion->getId())));	
    		}
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem($devolucion->getId(), $this->get("router")->generate("devolucion_show", array("devolucion" => $devolucion->getId())));
    	$breadcrumbs->addItem("Modificar".$devolucion->getId());
    
    	return $this->render('FarmaciaBundle:Devolucion:new.html.twig', array(
       			'devolucion' => $devolucion,
    			'form' => $form->createView(),
    	));
    }
    
    
    
} 