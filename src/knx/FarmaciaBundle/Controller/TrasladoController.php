<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Traslado;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Farmacia;
use knx\FarmaciaBundle\Form\TrasladoType;


class TrasladoController extends Controller
{
	public function ListAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
        
        return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
                'traslado'  => $traslado
        ));
    }
    
    public function NewAction()
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
    
    
    public function SaveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Nueva Traslado");
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
    	 
    	$traslado = new Traslado();
    	 
    	$request = $this->getRequest();
    	$form   = $this->createForm(new TrasladoType(), $traslado);
    	
    	
    	   
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    			$tipo_traslado = $traslado->getTipo();/*tipo de traslado*/
    			$cant_traslado = $traslado->getCant();/*cantidad de traslado*/
    			//die(var_dump($cant_traslado));
    			$inventario = $traslado->getInventario();/*Entidad inventario*/
    			$imv = $inventario->getImv();/*Entidad imv para llegar a la cantidad total*/
    			$cant_imv = $imv->getCantT();/*traigo cantidad total del imv*/
    			//die(var_dump($cant_imv));
    			    
    			 
    	
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
    
    public function ShowAction($traslado)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->find($traslado);
    	
    	
    	 
    	if (!$traslado) {
    		throw $this->createNotFoundException('El traslado solicitado no esta disponible.');
    	}
    		   	
    	    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem($traslado->getId());
    	 
    	return $this->render('FarmaciaBundle:Traslado:show.html.twig', array(
    			'traslado'  => $traslado,
    			
    			
    	));
    }
    
    public function EditAction($traslado)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->find($traslado);
    
   	   if (!$traslado) {
    		throw $this->createNotFoundException('El traslado solicitado no esta disponible.');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem($traslado->getId(), $this->get("router")->generate("traslado_show", array("traslado" => $traslado->getId())));
    	$breadcrumbs->addItem("Modificar".$traslado->getId());
    
    	$form   = $this->createForm(new TrasladoType(), $traslado);
    
    	return $this->render('FarmaciaBundle:Traslado:edit.html.twig', array(
    			'traslado' => $traslado,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function UpdateAction($traslado)
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
    	
    			$em = $this->getDoctrine()->getEntityManager();
    			
    			
    		if ($tipo_traslado=='T'){
    				if ($cant_imv < $cant_traslado){
    					
    				$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia-'.$cant_imv = $imv->getCantT().'');
    		        return $this->redirect($this->generateUrl('traslado_edit', array("traslado" => $traslado->getId())));
    					
    					
    					
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
    					return $this->redirect($this->generateUrl('traslado_edit', array("traslado" => $traslado->getId())));
    						
    						
    						
    				}else {
    				$em->persist($traslado);
    				$em->flush();
    
    				$this->get('session')->setFlash('ok', 'El traslado ha sido creada éxitosamente.');
    
    				return $this->redirect($this->generateUrl('traslado_show', array("traslado" => $traslado->getId())));
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