<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Traslado;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Farmacia;
use knx\FarmaciaBundle\Form\TrasladoType;
use knx\FarmaciaBundle\Form\TrasladoSearchType;


class TrasladoController extends Controller
{
	
	public function searchAction(){
	
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Traslados");
		$breadcrumbs->addItem("Busqueda");
		     
		
		
		$form   = $this->createForm(new TrasladoSearchType());
	
		return $this->render('FarmaciaBundle:Traslado:search.html.twig', array(
				'form'   => $form->createView()
	
		));
	
	}
	
	
	public function resultAction()
	{
		
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Farmacia");
		$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
		$breadcrumbs->addItem("Resultado Busqueda");
		
		
		
		$em = $this->getDoctrine()->getEntityManager();
		$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
		$request = $this->get('request');
		$fecha_inicio = $request->request->get('fecha_inicio');
		$fecha_fin = $request->request->get('fecha_fin');
		//die(print_r($fecha_inicio));
		
	
		if(trim($fecha_inicio)){
			$desde = explode('/',$fecha_inicio);
	
			//die(print_r($desde));
	
			if(!checkdate($desde[1],$desde[0],$desde[2])){
				$this->get('session')->setFlash('info', 'La fecha de inicio ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
						'traslado'  => $traslado
				));
	
			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de inicio no puede estar en blanco.');
			return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
					'traslado'  => $traslado
			));
			 
			$this->get('session')->setFlash('info',$this->get('sessio', 'La fecha de finalización ingresada es incorrecta.'));
		}
	
		if(trim($fecha_fin)){
			$hasta = explode('/',$fecha_fin);
	
			if(!checkdate($hasta[1],$hasta[0],$hasta[2])){
				return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
						'traslado'  => $traslado
				));
			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de finalización no puede estar en blanco.');
			return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
					'traslado'  => $traslado
			));
		}
	
		$query = "SELECT f FROM FarmaciaBundle:Traslado f WHERE
    				f.fecha >= :inicio AND
			    	f.fecha <= :fin
    				ORDER BY
    				f.fecha ASC";
	
		$dql = $em->createQuery($query);
	
	
	
		//die(print_r($dql));
	
		$dql->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
		$dql->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');
	
		$traslado = $dql->getResult();
		//die(var_dump($ingreso));
		//die("paso");
	
		if(!$traslado)
		{
			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');
	
			return $this->redirect($this->generateUrl('traslado_search'));
		}
	
		return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
				'trasfarma'  => $traslado
		));
	}
	
	
	
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$paginator  = $this->get('knp_paginator');
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
    	$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->findAll();
    	 
		if (!$traslado) {
			$this->get('session')->setFlash('info', 'No existen traslados');
		}	
        
        $traslado = $paginator->paginate($traslado,$this->getRequest()->query->get('page', 1), 10);
        
        
        return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
                'trasfarma'  => $traslado,
        		'farmacia'   => $farmacia
        ));
    }
    
    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Nueva Traslado");
    	 
    	$traslado = new Traslado();
    	 
    	$traslado->setFecha(new \datetime('now'));
    	$form   = $this->createForm(new TrasladoType(), $traslado);

    	return $this->render('FarmaciaBundle:Traslado:new.html.twig', array(
    			'traslado'=>$traslado,
    			'form'   => $form->createView()
    	));
    }
    
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Nueva Traslado");
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
    	 
    	$traslado = new Traslado();
    	$traslado->setFecha(new \datetime('now'));
    	 
    	$request = $this->getRequest();
    	$form   = $this->createForm(new TrasladoType(), $traslado);
    	   	    	   
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    			$tipo_traslado = $traslado->getTipo();/*tipo de movimiento*/
    			$cant_traslado = $traslado->getCant();/*cantidad de traslado*/
    			//die(var_dump($cant_traslado));
    			$inventario = $traslado->getInventario();/*Entidad inventario*/
    			$imv = $inventario->getImv();/*Entidad imv para llegar a la cantidad total*/
    			$cant_imv = $imv->getCantT();/*traigo cantidad total del imv*/
    			//die(var_dump($cant_imv));
    			$farmacia = $traslado->getFarmacia();
    			    
    			 
    	
    			$em = $this->getDoctrine()->getEntityManager();
    			if ($tipo_traslado=='T'){
    				
    				if ($cant_imv < $cant_traslado){
    					
    				$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia-'.$cant_imv = $imv->getCantT().'');
    		        return $this->redirect($this->generateUrl('traslado_new'));
    					
    					
    					
    				}else {
    					    					   					
    					$em->persist($traslado);
    					$em->flush();
    
    					$this->get('session')->setFlash('ok', 'El traslado ha sido creada éxitosamente.');
    
    				return $this->redirect($this->generateUrl('traslado_show', array("traslado" => $traslado->getId())));
     					}
    			}	
    			elseif (($tipo_traslado=='D')){
    				
    				if ($cant_imv < $cant_traslado){
    						
    					$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia-'.$cant_imv = $imv->getCantT().'');
    					return $this->redirect($this->generateUrl('traslado_new'));
    						
    						
    						
    				}else {
    						
    					$em->persist($traslado);
    					$em->flush();
    
    				$this->get('session')->setFlash('ok', 'El traslado ha sido creada éxitosamente.');
    
    				return $this->redirect($this->generateUrl('traslado_show', array("traslado" => $traslado->getId())));
         		 }
    			  
    			}		
    		
    		}
    	}
    	 
    	return $this->render('FarmaciaBundle:Traslado:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }
    
    public function showAction($traslado)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->find($traslado);
    	    	
    	$inventario = $traslado->getInventario();
    	$imv = $inventario->getImv();
    	$nombre_imv = $imv->getNombre();
    	$farmacia = $traslado->getFarmacia();
    	 
    	if (!$traslado) {
    		throw $this->createNotFoundException('El traslado solicitado no esta disponible.');
    	}
    		   	
    	    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem($nombre_imv, $this->get("router")->generate('traslado_show', array('traslado' => $traslado->getId())));
    	$breadcrumbs->addItem($farmacia->getNombre());
    	 
    	return $this->render('FarmaciaBundle:Traslado:show.html.twig', array(
    			'trasfarma'  => $traslado,
    			
    			
    	));
    }
    
    public function editAction($traslado)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->find($traslado);
    	    
   	   if (!$traslado) {
    		throw $this->createNotFoundException('El traslado solicitado no esta disponible.');
    	}
    	 
    	$inventario = $traslado->getInventario();
    	$imv = $inventario->getImv();
    	$nombre_imv = $imv->getNombre();
    	$farmacia = $traslado->getFarmacia();
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Modificar");
    	$breadcrumbs->addItem($nombre_imv, $this->get("router")->generate('traslado_show', array('traslado' => $traslado->getId())));
    	    
    	$form   = $this->createForm(new TrasladoType(), $traslado);
    
    	return $this->render('FarmaciaBundle:Traslado:edit.html.twig', array(
    			'trasfarma' => $traslado,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function updateAction($traslado)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->find($traslado);
    	    
        if (!$traslado) {
    		throw $this->createNotFoundException('El traslado solicitado no esta disponible.');
    	}
    
    	$form = $this->createForm(new TrasladoType(), $traslado);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    			$tipo_traslado = $traslado->getTipo();/*tipo de traslado*/
    			$cant_traslado = $traslado->getCant();/*cantidad de traslado*/
    			//die(var_dump($cant_traslado));
    			$inventario = $traslado->getInventario();/*Entidad inventario*/
    			$imv = $inventario->getImv();/*Entidad imv para llegar a la cantidad total*/
    			$cant_imv = $imv->getCantT();/*traigo cantidad total del imv*/
    			$farmacia = $traslado->getFarmacia();
    			 
    			$em = $this->getDoctrine()->getEntityManager();
    			
    			
    		if ($tipo_traslado=='T'){
    				if ($cant_imv < $cant_traslado){
    					
    				$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia-'.$cant_imv = $imv->getCantT().'');
    		        return $this->redirect($this->generateUrl('traslado_edit', array('inventario' => $inventario->getId(), 'farmacia' => $farmacia->getId())));
    					
    					
    					
    				}else {
    				$em->persist($traslado);
    				$em->flush();
    
    				$this->get('session')->setFlash('ok', 'El traslado ha sido modificado éxitosamente.');
    
    				return $this->redirect($this->generateUrl('traslado_show', array('traslado' => $traslado->getId())));
    				}
    			}	
    			elseif (($tipo_traslado=='D')){
    				
    				if ($cant_imv < $cant_traslado){
    						
    					$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia-'.$cant_imv = $imv->getCantT().'');
    					return $this->redirect($this->generateUrl('traslado_edit', array('traslado' => $traslado->getId())));
    						
    						
    						
    				}else {
    				$em->persist($traslado);
    				$em->flush();
    
    				$this->get('session')->setFlash('ok', 'El traslado ha sido modificado éxitosamente.');
    
    				return $this->redirect($this->generateUrl('traslado_show', array('inventario' => $inventario->getId(), 'farmacia' => $farmacia->getId())));
    				}
    				
    			}	
       	
    			
    		}
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem($traslado->getId(), $this->get("router")->generate("traslado_show", array("traslado" => $traslado->getId())));
    	$breadcrumbs->addItem("Modificar".$traslado->getId());
    
    	return $this->render('FarmaciaBundle:Traslado:new.html.twig', array(
       			'traslado' => $traslado,
    			'form' => $form->createView(),
    	));
    }
    
    
    
} 