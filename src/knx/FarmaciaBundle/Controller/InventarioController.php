<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Ingreso;
use knx\FarmaciaBundle\Form\InventarioType;

class InventarioController extends Controller
{
	public function ListAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("inventario_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $inventario = $em->getRepository('FarmaciaBundle:Inventario')->findAll();
        
        return $this->render('FarmaciaBundle:Inventario:list.html.twig', array(
                'Inventario'  => $inventario
        ));
    }
    
    public function NewAction($ingreso)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("inventario_list"));
    	$breadcrumbs->addItem("Nueva Inventario");
    	
    	$inventario = new Inventario();
    	$form   = $this->createForm(new InventarioType(), $inventario);
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
    	
		
    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
    			'ingreso' => $ingreso,    			
    			'form'   => $form->createView()
    	));
    }
    
    
    public function SaveAction($ingreso)
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

	    		$inventario->setIngreso($ingreso);

	    		$em->persist($inventario);
	    		$em->flush();

	    		$this->get('session')->setFlash('ok', 'El Invenatrio ha sido creado éxitosamente.');

	    		return $this->redirect($this->generateUrl('ingreso_show', array("ingreso" => $ingreso->getId())));
	    	}
    	}

    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
    			'ingreso' => $ingreso,
    			'form'   => $form->createView()
    	));    
    }
    
    public function ShowAction($inventario)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	//$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
    	 
    	 
    	if (!$inventario) {
    		throw $this->createNotFoundException('La inventario solicitada no esta disponible.');
    	}
    	
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->find($inventario);
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($inventario->getId());
    	 
    	return $this->render('FarmaciaBundle:Inventario:show.html.twig', array(
    		//	'ingreso'=> $ingreso,
    			'inventario'  => $inventario
    	));
    }
    
    public function EditAction($inventario)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->find($inventario);
    
   	   if (!$inventario) {
    		throw $this->createNotFoundException('La inventario solicitada no esta disponible.');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($inventario->getId(), $this->get("router")->generate("inventario_show", array("inventario" => $inventario->getId())));
    	$breadcrumbs->addItem("Modificar".$inventario->getId());
    
    	$form   = $this->createForm(new InventarioType(), $inventario);
    
    	return $this->render('FarmaciaBundle:Inventario:edit.html.twig', array(
    			'inventario' => $inventario,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function UpdateAction($inventario)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->find($inventario);
    
        if (!$inventario) {
    		throw $this->createNotFoundException('La inventario solicitada no esta disponible.');
    	}
    
    	$form = $this->createForm(new InventarioType(), $inventario);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($inventario);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'La inventario ha sido creada éxitosamente.');
    
    			return $this->redirect($this->generateUrl('inventario_show', array("inventario" => $inventario->getId())));	
    		}
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($inventario->getId(), $this->get("router")->generate("inventario_show", array("Inventario" => $inventario->getId())));
    	$breadcrumbs->addItem("Modificar".$inventario->getId());
    
    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
       			'inventario' => $inventario,
    			'form' => $form->createView(),
    	));
    }
    
    
public function deleteAction($inventario)
    {
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->find( $inventario);
    	//die(var_dump($inventario));
    	if (!$inventario) {
    		throw $this->createNotFoundException('El Inventario solicitado no existe.');
    	}
    	 
    	    	 
    	$em->remove($inventario);
    	$em->flush();
    	 
    	$this->get('session')->setFlash('info', 'El inventario ha sido eliminado.');
    	 
    	return $this->redirect($this->generateUrl('ingreso_list'));
    	 
    	    	
    }
    
   
} 