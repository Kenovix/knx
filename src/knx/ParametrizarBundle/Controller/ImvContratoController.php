<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\ImvContrato;
use knx\ParametrizarBundle\Form\ImvContratoType;

class ImvContratoController extends Controller
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
    	$breadcrumbs->addItem("Nuevo medicamento");
    	
    	$imv_contrato = new ImvContrato();
    	$form   = $this->createForm(new ImvContratoType(), $imv_contrato);
    	
    	return $this->render('ParametrizarBundle:ImvContrato:new.html.twig', array(
    			'contrato' => $contrato,
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction($contrato)
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
    	$breadcrumbs->addItem("Nuevo medicamento");
    	
    	$request = $this->getRequest();
    	
    	$imv_contrato = new ImvContrato();
    	$form   = $this->createForm(new ImvContratoType(), $imv_contrato);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		
	    		$existe_imv_contrato = $em->getRepository('ParametrizarBundle:ImvContrato')->findBy(array('contrato' => $contrato->getId(), 'imv' => $imv_contrato->getImv()->getId()));
	    		 
	    		if(!$existe_imv_contrato){
	    			
	    			$imv_contrato->setContrato($contrato);
	    		
		    		$em->persist($imv_contrato);
		    		$em->flush();
	    		
	    			$this->get('session')->setFlash('ok', 'La contratación ha sido creada éxitosamente.');
	    		
	    			return $this->redirect($this->generateUrl('imv_contrato_show', array("contrato" => $imv_contrato->getContrato()->getId(), "imv" => $imv_contrato->getImv()->getId())));
	    		}else{
	    			$this->get('session')->setFlash('info', 'El insumo ya ha sido asociado anteriormente.');
	    		
	    			return $this->render('ParametrizarBundle:ImvContrato:new.html.twig', array(
	    					'contrato' => $contrato,
	    					'form' => $form->createView()
	    			));
	    		}
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:ImvContrato:new.html.twig', array(
    			'contrato' => $contrato,
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($contrato, $imv)
    {    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imv_contrato = $em->getRepository('ParametrizarBundle:ImvContrato')->findOneBy(array("contrato" => $contrato, "imv" => $imv));
    	
    	if (!$imv_contrato) {
    		throw $this->createNotFoundException('El insumo contratado no esta disponible.');
    	}
    	
    	$cliente = $imv_contrato->getContrato()->getCliente();
    	$contrato = $imv_contrato->getContrato();

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array('cliente' => $cliente->getId())));
    	$breadcrumbs->addItem('Contrato '.$contrato->getNumero(), $this->get("router")->generate("contrato_show", array('contrato' => $contrato->getId())));
    	$breadcrumbs->addItem($imv_contrato->getImv()->getNombre());

    	return $this->render('ParametrizarBundle:ImvContrato:show.html.twig', array(
    			'contratado' => $imv_contrato
    	));
    }
    
    public function editAction($contrato, $imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imv_contrato = $em->getRepository('ParametrizarBundle:ImvContrato')->findOneBy(array("contrato" => $contrato, "imv" => $imv));
    
    	if (!$imv_contrato) {
    		throw $this->createNotFoundException('el insumo contratado no existe');
    	}
    	
    	$cliente = $imv_contrato->getContrato()->getCliente();
    	$contrato = $imv_contrato->getContrato();
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array('cliente' => $cliente->getId())));
    	$breadcrumbs->addItem('Contrato '.$contrato->getNumero(), $this->get("router")->generate("contrato_show", array('contrato' => $contrato->getId())));
    	$breadcrumbs->addItem($imv_contrato->getImv()->getNombre(), $this->get("router")->generate("imv_contrato_show", array('contrato' => $contrato->getId(), 'imv' => $imv_contrato->getImv()->getId())));
    	$breadcrumbs->addItem("Modificar ");
    
    	$form = $this->createForm(new ImvContratoType(), $imv_contrato);
    
    	return $this->render('ParametrizarBundle:ImvContrato:edit.html.twig', array(
    			'contratado' => $imv_contrato,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($contrato, $imv)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imv_contrato = $em->getRepository('ParametrizarBundle:ImvContrato')->findOneBy(array("contrato" => $contrato, "imv" => $imv));
    
    	if (!$imv_contrato) {
    		throw $this->createNotFoundException('El insumo contratado no existe');
    	}
    	
    	$cliente = $imv_contrato->getContrato()->getCliente();
    	$contrato = $imv_contrato->getContrato();
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array('cliente' => $cliente->getId())));
    	$breadcrumbs->addItem('Contrato '.$contrato->getNumero(), $this->get("router")->generate("contrato_show", array('contrato' => $contrato->getId())));
    	$breadcrumbs->addItem($imv_contrato->getImv()->getNombre(), $this->get("router")->generate("imv_contrato_show", array('contrato' => $contrato->getId(), 'imv' => $imv_contrato->getImv()->getId())));
    	$breadcrumbs->addItem("Modificar ");

    	$form = $this->createForm(new ImvContratoType(), $imv_contrato);
    	$request = $this->getRequest();

    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

	    	if ($form->isValid()) {

	    		$em->persist($imv_contrato);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La contratación ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('imv_contrato_edit', array('contrato' => $imv_contrato->getContrato()->getId(), 'imv' => $imv_contrato->getImv()->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:ImvContrato:edit.html.twig', array(
    			'contratado' => $imv_contrato,
    			'form' => $form->createView()
    	));
    }
}