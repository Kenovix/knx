<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Devolucion;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Form\DevolucionType;
use knx\FarmaciaBundle\Form\DevolucionSearchType;


class DevolucionController extends Controller
{
	
	public function searchAction(){
	
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Farmacia");
		$breadcrumbs->addItem("Busqueda");
			
		$form   = $this->createForm(new DevolucionSearchType());
	
		return $this->render('FarmaciaBundle:Devolucion:search.html.twig', array(
				'form'   => $form->createView()
	
		));
	
	}
	
	
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devoluciones", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem("Listado");
    	$paginator  = $this->get('knp_paginator');
    	 
    	$em = $this->getDoctrine()->getEntityManager();    
        $devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findAll();
        $devolucion = $paginator->paginate($devolucion,$this->getRequest()->query->get('page', 1), 10);
        
        
        return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
                'devolucion'  => $devolucion
        ));
    }
    
    
    public function resultAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findAll();
    	$request = $this->get('request');
    	$fecha_inicio = $request->request->get('fecha_inicio');
    	$fecha_fin = $request->request->get('fecha_fin');
    
    	 
    	if(trim($fecha_inicio)){
    		$desde = explode('/',$fecha_inicio);
    
    		//die(print_r($desde));
    
    		if(!checkdate($desde[1],$desde[0],$desde[2])){
    			$this->get('session')->setFlash('info', 'La fecha de inicio ingresada es incorrecta.');
    			return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
    					'devolucion'  => $devolucion
    			));
    
    		}
    	}else{
    		$this->get('session')->setFlash('info', 'La fecha de inicio no puede estar en blanco.');
    		return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
    				'devolucion'  => $devolucion
    		));
    		 
    		$this->get('session')->setFlash('info',$this->get('sessio', 'La fecha de finalización ingresada es incorrecta.'));
    	}
    
    	if(trim($fecha_fin)){
    		$hasta = explode('/',$fecha_fin);
    
    		if(!checkdate($hasta[1],$hasta[0],$hasta[2])){
    			return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
    					'devolucion'  => $devolucion
    			));
    		}
    	}else{
    		$this->get('session')->setFlash('info', 'La fecha de finalización no puede estar en blanco.');
    		return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
    				'devolucion'  => $devolucion
    		));
    	}
    	 
    	$query = "SELECT f FROM FarmaciaBundle:Devolucion f WHERE
    				f.fecha >= :inicio AND
			    	f.fecha <= :fin
    				ORDER BY
    				f.fecha ASC";
    
    	$dql = $em->createQuery($query);
    
    	 
    
    	//die(print_r($dql));
    
    	$dql->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    	$dql->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');
    
    	$devolucion = $dql->getResult();
    	//die(var_dump($ingreso));
    	//die("paso");
    
    	if(!$devolucion)
    	{
    		$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');
    
    		return $this->redirect($this->generateUrl('devolucion_search'));
    	}
    	 
    	return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
    			'devolucion' => $devolucion,
    	));
    }
    
    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
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
    
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem("Nueva Devolucion");
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findAll();
    	 
    	$devolucion = new Devolucion();
    	 
    	$request = $this->getRequest();
    	$form   = $this->createForm(new DevolucionType(), $devolucion);
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		
    		
    		 
    		if ($form->isValid()) {
    			
    			$cant_devolucion = $devolucion->getCant();/*cantidad de devolucion*/
    			$inventario = $devolucion->getInventario();/*Entidad inventario*/
    			$cant_inventario = $inventario->getCant();
    			$imv = $inventario->getImv();/*Entidad imv para llegar a la cantidad total*/
    			$cant_imv = $imv->getCantT();/*traigo cantidad total del imv*/
    	
    			$em = $this->getDoctrine()->getEntityManager();
    			if ($cant_imv < $cant_devolucion){
    					
    				$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia-'.$cant_imv = $imv->getCantT().'');
    				return $this->redirect($this->generateUrl('devolucion_new'));
    			}
    			else {

    			 					
    				$imv->setCantT($cant_imv-$cant_devolucion);
    				$inventario->setCant($cant_inventario-$cant_devolucion);	
    					
    					
    			
    			$em->persist($devolucion);
    			$em->persist($imv);
    			$em->persist($inventario);
    			 
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El devolucion ha sido creada éxitosamente.');
    
    			return $this->redirect($this->generateUrl('devolucion_show', array("devolucion" => $devolucion->getId())));	
    		}
    	}
    	 
    	return $this->render('FarmaciaBundle:Devolucion:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }
   } 
    public function showAction($devolucion)
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
    
} 