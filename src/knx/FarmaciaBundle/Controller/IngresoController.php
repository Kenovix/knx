<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Ingreso;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Imv;
use knx\FarmaciaBundle\Form\IngresoType;


class IngresoController extends Controller
{
	public function ListAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->findAll();
        
        return $this->render('FarmaciaBundle:Ingreso:list.html.twig', array(
                'ingreso'  => $ingreso
        ));
    }
    
    public function NewAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem("Nueva Ingreso");
    	
    	$ingreso = new Ingreso();
    	$ingreso->setFecha(new \datetime('now'));
    	$form   = $this->createForm(new IngresoType(), $ingreso);

    	return $this->render('FarmaciaBundle:Ingreso:new.html.twig', array(
    			'ingreso'=>$ingreso,
    			'form'   => $form->createView()
    	));
    }
    
    
    public function SaveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem("Nueva Ingreso");
    	 
    	$ingreso = new Ingreso();
    	 
    	$request = $this->getRequest();
    	$form   = $this->createForm(new IngresoType(), $ingreso);
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($ingreso);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El ingreso ha sido creada éxitosamente.');
    
    			return $this->redirect($this->generateUrl('ingreso_show', array("ingreso" => $ingreso->getId())));	
    		}
    	}
    	 
    	return $this->render('FarmaciaBundle:Ingreso:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }
    
    public function ShowAction($ingreso)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
    	
    	
    	 
    	if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}
    	
    	$inventarios = $em->getRepository('FarmaciaBundle:Inventario')->findByIngreso($ingreso);
    	//$inventario = new Inventario();
    	
    	
    	//$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findByIngreso($ingreso);
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($ingreso->getnumFact());
    	 
    	return $this->render('FarmaciaBundle:Ingreso:show.html.twig', array(
    			'ingreso'  => $ingreso,
    			'inventarios' => $inventarios
    			
    	));
    }
    
    public function EditAction($ingreso)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
    
   	   if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($ingreso->getnumFact(), $this->get("router")->generate("ingreso_show", array("ingreso" => $ingreso->getId())));
    	$breadcrumbs->addItem("Modificar".$ingreso->getnumFact());
    
    	$form   = $this->createForm(new IngresoType(), $ingreso);
    
    	return $this->render('FarmaciaBundle:Ingreso:edit.html.twig', array(
    			'ingreso' => $ingreso,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function UpdateAction($ingreso)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
    
        if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}
    
    	$form = $this->createForm(new IngresoType(), $ingreso);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($ingreso);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El ingreso ha sido modificado éxitosamente.');
    
    			return $this->redirect($this->generateUrl('ingreso_show', array("ingreso" => $ingreso->getId())));	
    		}
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($ingreso->getnumFact(), $this->get("router")->generate("ingreso_show", array("ingreso" => $ingreso->getId())));
    	$breadcrumbs->addItem("Modificar".$ingreso->getnumFact());
    
    	return $this->render('FarmaciaBundle:Ingreso:new.html.twig', array(
       			'ingreso' => $ingreso,
    			'form' => $form->createView(),
    	));
    }
    
    
    
} 