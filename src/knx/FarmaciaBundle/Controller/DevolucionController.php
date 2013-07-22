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
       
        if (!$devolucion) {
        	$this->get('session')->setFlash('info', 'No existen devoluciones');
        }
        
        
        $devolucion = $paginator->paginate($devolucion,$this->getRequest()->query->get('page', 1), 10);
   		 
        
        return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
                'devfarma'  => $devolucion
        		
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
    			'devfarma' => $devolucion,
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
    	$devolucion->setFecha(new \datetime('now'));
    	
    	
    	$request = $this->getRequest();
    	$form   = $this->createForm(new DevolucionType(), $devolucion);
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		
    		
    		 
    		if ($form->isValid()) {
    			
    			$cant_devolucion = $devolucion->getCant();/*cantidad de devolucion*/
    			$inventario = $devolucion->getInventario();/*Entidad inventario*/
    			$cant_inventario = $inventario->getCant();/*cantidad inventario*/
    			$ingreso = $inventario->getIngreso();
    			//die(var_dump($ingreso));
    			$imv = $inventario->getImv();/*Entidad imv para llegar a la cantidad total*/
    			$cant_imv = $imv->getCantT();/*traigo cantidad total del imv*/
    			$proveedor = $ingreso->getProveedor();
    			$precio_compra = $inventario->getPrecioCompra();
    			$proveedor_ingresado = $devolucion->getProveedor();
    			$inventariop = $devolucion->getInventario();/*Entidad inventario*/
    			$cant_inventariop = $inventariop->getCant();/*cantidad inventario*/
    			    
    			$em = $this->getDoctrine()->getEntityManager();
    			
    			if($proveedor == $proveedor_ingresado){
    				
    				
    				if ($cant_inventario < $cant_devolucion){
    						
    					$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia con proveedor-'.$cant_inventario = $inventario->getCant().'');
    					return $this->redirect($this->generateUrl('devolucion_new'));
    				}
    				else {
    				
    						
    					$imv->setCantT($cant_imv-$cant_devolucion);
    					$inventario->setCant($cant_inventario-$cant_devolucion);
    					
    					$inventariop = $devolucion->getInventario();/*Entidad inventario*/
    					$cant_inventariop = $inventariop->getCant();/*cantidad inventario*/
    					//die(var_dump($cant_inventariop));
    					    	
    					$inventariop->setPrecioTotal($cant_inventariop * $precio_compra);	
    					//die(var_dump($inventario));	
    					 
    					$em->persist($devolucion);
    					$em->persist($imv);
    					$em->persist($inventario);
    					$em->persist($inventariop);
    				
    					$em->flush();
    				
    					$this->get('session')->setFlash('ok', 'El devolucion ha sido creada éxitosamente.');
    				
    					return $this->redirect($this->generateUrl('devolucion_show', array('devolucion' => $devolucion->getId())));
    				}
    				
    				
    				
    				
    			}
    			else{
    				
    				$this->get('session')->setFlash('error','Item para devolucion no corresponde a Proveedor selecionado-'.$proveedor_ingresado = $devolucion->getProveedor().-'Perteneca a:'.$proveedor = $ingreso->getProveedor().'');
    				return $this->redirect($this->generateUrl('devolucion_new'));
    			}
    			
    		
    			
    				
    		
    			}
    	}
    	 
    	return $this->render('FarmaciaBundle:Devolucion:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }
    

    public function showAction($devolucion)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->find($devolucion);
    	    	
       	$inventario = $devolucion->getInventario();/*Entidad inventario*/
    	
    	
    	if (!$devolucion) {
    		throw $this->createNotFoundException('El devolucion solicitado no esta disponible.');
    	}
    		   	
    	    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devolucions", $this->get("router")->generate("devolucion_list"));
    	//$breadcrumbs->addItem($devolucion->getId());
    	 
    	return $this->render('FarmaciaBundle:Devolucion:show.html.twig', array(
    			'devfarma'  => $devolucion,
    			'inventario' => $inventario
    			
    			
    	));
    }   
    
} 