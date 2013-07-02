<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\ContratoCargo;
use knx\ParametrizarBundle\Form\ContratoCargoType;

class ContratoCargoController extends Controller
{
    public function newAction($contrato)
    {    	
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$contrato = $em->getRepository('ParametrizarBundle:Contrato')->find($contrato);
    	
    	if (!$contrato) {
    		throw $this->createNotFoundException('El contrato solicitado no esta disponible.');
    	}
    	
    	$cliente = $contrato->getCliente();
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array('cliente' => $cliente->getId())));
    	$breadcrumbs->addItem('Contrato '.$contrato->getNumero(), $this->get("router")->generate("contrato_show", array('contrato' => $contrato->getId())));
    	$breadcrumbs->addItem("Nueva actividad");
    	
    	$contrato_cargo = new ContratoCargo();
    	$form   = $this->createForm(new ContratoCargoType(), $contrato_cargo);
    	
    	return $this->render('ParametrizarBundle:ContratoCargo:new.html.twig', array(
    			'contrato' => $contrato,
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction($contrato)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$request = $this->getRequest();
    	
    	$contrato_cargo = new ContratoCargo();    	
    	$form = $this->createForm(new ContratoCargoType(), $contrato_cargo);
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$contrato = $em->getRepository('ParametrizarBundle:Contrato')->find($contrato);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		
	    		$existe_contrato_cargo = $em->getRepository('ParametrizarBundle:ContratoCargo')->findBy(array('contrato' => $contrato->getId(), 'cargo' => $contrato_cargo->getCargo()->getId()));
	    		 
	    		if(!$existe_contrato_cargo){
	    			
	    			$contrato_cargo->setContrato($contrato);
	    		
		    		$em->persist($contrato_cargo);
		    		$em->flush();
	    		
	    			$this->get('session')->setFlash('ok', 'La contratación ha sido creada éxitosamente.');
	    		
	    			return $this->redirect($this->generateUrl('contrato_cargo_show', array("contrato" => $contrato_cargo->getContrato()->getId(), "cargo" => $contrato_cargo->getCargo()->getId())));
	    		}else{
	    			$this->get('session')->setFlash('info', 'El cargo ya ha sido asociado anteriormente.');
	    		
	    			return $this->render('ParametrizarBundle:ContratoCargo:new.html.twig', array(
	    					'contrato' => $contrato,
	    					'form' => $form->createView()
	    			));
	    		}
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:ContratoCargo:new.html.twig', array(
    			'contrato' => $contrato,
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($contrato, $cargo)
    {    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$contrato_cargo = $em->getRepository('ParametrizarBundle:ContratoCargo')->findOneBy(array("contrato" => $contrato, "cargo" => $cargo));
    	
    	if (!$contrato_cargo) {
    		throw $this->createNotFoundException('La actividad contratada no esta disponible.');
    	}
    	
    	$cliente = $contrato_cargo->getContrato()->getCliente();
    	$contrato = $contrato_cargo->getContrato();

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array('cliente' => $cliente->getId())));
    	$breadcrumbs->addItem('Contrato '.$contrato->getNumero(), $this->get("router")->generate("contrato_show", array('contrato' => $contrato->getId())));
    	$breadcrumbs->addItem($contrato_cargo->getCargo()->getNombre());

    	return $this->render('ParametrizarBundle:ContratoCargo:show.html.twig', array(
    			'contratado' => $contrato_cargo
    	));
    }
    
    public function editAction($contrato, $cargo)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$contrato_cargo = $em->getRepository('ParametrizarBundle:ContratoCargo')->findOneBy(array("contrato" => $contrato, "cargo" => $cargo));
    
    	if (!$contrato_cargo) {
    		throw $this->createNotFoundException('La actividad contratada no existe');
    	}
    	
    	$cliente = $contrato_cargo->getContrato()->getCliente();
    	$contrato = $contrato_cargo->getContrato();
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array('cliente' => $cliente->getId())));
    	$breadcrumbs->addItem('Contrato '.$contrato->getNumero(), $this->get("router")->generate("contrato_show", array('contrato' => $contrato->getId())));
    	$breadcrumbs->addItem($contrato_cargo->getCargo()->getNombre(), $this->get("router")->generate("contrato_cargo_show", array('contrato' => $contrato->getId(), 'cargo' => $contrato_cargo->getCargo()->getId())));
    	$breadcrumbs->addItem("Modificar ");
    
    	$form = $this->createForm(new ContratoCargoType(), $contrato_cargo);
    
    	return $this->render('ParametrizarBundle:ContratoCargo:edit.html.twig', array(
    			'contratado' => $contrato_cargo,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($contrato, $cargo)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$contrato_cargo = $em->getRepository('ParametrizarBundle:ContratoCargo')->findOneBy(array("contrato" => $contrato, "cargo" => $cargo));
    
    	if (!$contrato_cargo) {
    		throw $this->createNotFoundException('La actividad contratada no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Contrato");
    	$breadcrumbs->addItem("Modificar ");
    
    	$form = $this->createForm(new ContratoCargoType(), $contrato_cargo);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($contrato_cargo);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La contratación ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('contrato_cargo_edit', array('contrato' => $contrato_cargo->getContrato()->getId(), 'cargo' => $contrato_cargo->getCargo()->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:ContratoCargo:edit.html.twig', array(
    			'contratado' => $contrato_cargo,
    			'form' => $form->createView()
    	));
    }
}