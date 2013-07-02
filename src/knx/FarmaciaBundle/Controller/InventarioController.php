<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Imv;
use knx\FarmaciaBundle\Entity\Ingreso;
use knx\FarmaciaBundle\Entity\Traslado;
use knx\FarmaciaBundle\Form\UpdateInventarioType;
use knx\FarmaciaBundle\Form\InventarioType;



class InventarioController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("inventario_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $inventario = $em->getRepository('FarmaciaBundle:Inventario')->findAll();
        
        return $this->render('FarmaciaBundle:Inventario:list.html.twig', array(
                'Inventario'  => $inventario
        ));
    }
    
    public function newAction($ingreso)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("inventario_list"));
    	$breadcrumbs->addItem("Nueva Inventario");
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
    	
    	if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}
    	
    	$inventario = new Inventario();
    	$form   = $this->createForm(new InventarioType(), $inventario);
    	
    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
    			'ingreso' => $ingreso,
    			'form'   => $form->createView()
    	));
    }
    
    
    public function saveAction($ingreso)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem("Nueva Inventario");
    	 
    	$request = $this->getRequest();
    	$inventario  = new Inventario();
    	$form    = $this->createForm(new InventarioType(), $inventario);
    	
    	$em = $this->getDoctrine()->getEntityManager();        
        $ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
        
        
    if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		
	    		$imv = $inventario->getImv();
	    		
	    		$cantidad_actual = $imv->getCantT();
	    		$cantidad_ingresada = $inventario->getCant();
	    		
	    		$precio_compra = $inventario->getPrecioCompra();
	    		$precio_venta = $inventario->getPrecioVenta();
	    		
	    		
	    		if($precio_venta<$precio_compra){
	    			
	    			$this->get('session')->setFlash('error', 'El precio de venta no puede ser menor a precio de compra.');
	    			
	    			return $this->redirect($this->generateUrl('inventario_new', array("ingreso" => $ingreso->getId())));
	    			
	    		}else{
	    		
	    		
	    		$imv->setCantT($cantidad_actual+$cantidad_ingresada);
	    		$inventario->setPrecioTotal($cantidad_ingresada * $precio_compra);
	    		$inventario->setIngreso($ingreso);
	    		
	    		

	    		$em->persist($inventario);
	    		$em->persist($imv);
	    		
	    		$em->flush();

	    		$this->get('session')->setFlash('ok', 'El Invenatrio ha sido creado éxitosamente.');

	    		}return $this->redirect($this->generateUrl('ingreso_show', array("ingreso" => $ingreso->getId())));
	    	}
    	}

    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
    			'ingreso' => $ingreso,
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($ingreso)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
    	 
    	 
    	if (!$inventario) {
    		throw $this->createNotFoundException('La inventario solicitada no esta disponible.');
    	}
    	
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findBy(array('ingreso' => $ingreso));
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($inventario->getId());
    	 
    	return $this->render('FarmaciaBundle:Inventario:show.html.twig', array(
    			'ingreso'=> $ingreso,
    			'inventario'  => $inventario
    	));
    }
    
    public function editAction($inventario)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->find($inventario);
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findBy(array("inventario"=>$inventario->getId()));
    	 
    	//die(var_dump($inventario));
    	
    
   	   if (!$inventario) {
    		throw $this->createNotFoundException('El inventario solicitada no esta disponible.');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($inventario->getId(), $this->get("router")->generate("inventario_edit", array("inventario" => $inventario->getId())));
    	$breadcrumbs->addItem("Modificar".$inventario->getId());
    
       if ($traslado == null){
    				 
    				$form   = $this->createForm(new UpdateInventarioType(), $inventario);
    				 
    				 
    			}else {

						$this->get('session')->setFlash('error','El Item no se puede modificar ya que ha sido trasladado');    			
						return $this->redirect($this->generateUrl('ingreso_list'));
						
    			}
    			
    
       
    	return $this->render('FarmaciaBundle:Inventario:edit.html.twig', array(
    			'inventario' => $inventario,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function updateAction($inventario)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->find($inventario);
    	    
        if (!$inventario) {
    		throw $this->createNotFoundException('La inventario solicitada no esta disponible.');
    	}
    
    	$form = $this->createForm(new UpdateInventarioType(), $inventario);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		 
    	$cantidad_inventario = $inventario->getcant();
    		//die(var_dump($imv));
    	$precio_compra = $inventario->getPrecioCompra();
    	$imv = $inventario->getImv();
    	
    	   
    		$form->bind($request);
    		
    		
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    			$imv = $inventario->getImv();
    			$cantidad_actual = $imv->getCantT();
    			$cantidad_ingresada = $inventario->getCant();
    			
    			//die(var_dump($medicamento_ingresado));
    			$precio_compra = $inventario->getPrecioCompra();
    			$precio_venta = $inventario->getPrecioVenta();
    			 
    			 
    			if($precio_venta<$precio_compra){
    			
    				$this->get('session')->setFlash('error', 'El precio de venta no puede ser menor a precio de compra.');
    			
    				return $this->redirect($this->generateUrl('inventario_edit', array("inventario" => $inventario->getId())));
    			
    			}else{
    			
    			
    			     			
    			$inventario->setPrecioTotal($cantidad_ingresada * $precio_compra);

    			
    			if ($cantidad_ingresada < $cantidad_inventario){
    				 
    				$cantidad_diferencia =  $cantidad_inventario - $cantidad_ingresada;
    				 
    				 
    				 
    			}else {
    				$cantidad_diferencia =  $cantidad_ingresada - $cantidad_inventario;
    			}
    			
    					
    			if ($cantidad_ingresada < $cantidad_inventario){
    					
    				$imv->setCantT($cantidad_actual-$cantidad_diferencia);
    					
    					
    					
    			}else {
    				$imv->setCantT($cantidad_actual+$cantidad_diferencia);
    			}
		
 		  		
	    		$em->persist($inventario);
	    		$em->persist($imv);
	    		
	    		$em->flush();
    
    			$this->get('session')->setFlash('ok', 'La inventario ha sido creada éxitosamente.');
    
    			return $this->redirect($this->generateUrl('inventario_edit', array("inventario" => $inventario->getId())));	
    		}
    		

    		return $this->render('FarmaciaBundle:Inventario:edit.html.twig', array(
    				'inventario' => $inventario,
    				'form'   => $form->createView()
    		));
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($inventario->getId(), $this->get("router")->generate("inventario_edit", array("Inventario" => $inventario->getId())));
    	$breadcrumbs->addItem("Modificar".$inventario->getId());
    
    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
       			'inventario' => $inventario,
    			'form' => $form->createView(),
    	));
    }
    }    
    
public function deleteAction($inventario)
    {
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->find( $inventario);
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findBy(array("inventario"=>$inventario->getId()));
    	 
    	
    	$imv = $inventario->getImv();
    	$cantidad_inventario = $inventario->getcant();
    	$cantidad_actual = $imv->getCantT();
    	$precio_compra = $inventario->getPrecioCompra();
    	 
    	
    	//die(var_dump($cantidad_actual));
    	if (!$inventario) {
    		throw $this->createNotFoundException('El Inventario solicitado no existe.');
    	}
    	if ($traslado == null){    	  		
    	
    	if ($cantidad_inventario < $cantidad_actual or $cantidad_inventario == $cantidad_actual){
    			
    		$imv->setCantT($cantidad_actual-$cantidad_inventario);    		    			
    		
    			
    	}
    	$em->remove($inventario);
    	$em->flush();
    	 
    	$this->get('session')->setFlash('ok', 'El inventario ha sido eliminado.');
    	 
    	return $this->redirect($this->generateUrl('ingreso_list'));
    			
    			
    	}else {
    	
    		$this->get('session')->setFlash('error','El Item no se puede eliminar ya que ha sido trasladado');
    		return $this->redirect($this->generateUrl('ingreso_list'));
    	
    	} 
    	    	 
    	
    	 
    	    	
    }
    
   
} 