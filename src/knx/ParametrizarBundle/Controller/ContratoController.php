<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\Contrato;
use knx\ParametrizarBundle\Form\ContratoType;

class ContratoController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $cliente = $em->getRepository('ParametrizarBundle:Cliente')->findAll();
        
        return $this->render('ParametrizarBundle:Cliente:list.html.twig', array(
                'clientes'  => $cliente
        ));
    }

    public function newAction($cliente)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Contrato");
    	$breadcrumbs->addItem("Nuevo");
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$cliente = $em->getRepository('ParametrizarBundle:Cliente')->find($cliente);
    	
    	if (!$cliente) {
    		throw $this->createNotFoundException('El cliente solicitado no esta disponible.');
    	}
    	
    	$contrato = new Contrato();
    	$form   = $this->createForm(new ContratoType(), $contrato);
    	
    	return $this->render('ParametrizarBundle:Contrato:new.html.twig', array(
    			'cliente' => $cliente,
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction($cliente)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$request = $this->getRequest();
    	
    	$contrato = new Contrato();    	
    	$form = $this->createForm(new ContratoType(), $contrato);
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$cliente = $em->getRepository('ParametrizarBundle:Cliente')->find($cliente);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		
	    		$contrato->setCliente($cliente);
	    		
	    		$em->persist($contrato);
	    		$em->flush();
	    
	    		$this->get('session')->setFlash('ok', 'El contrato ha sido creado éxitosamente.');
	    
	    		return $this->redirect($this->generateUrl('cliente_show', array("cliente" => $cliente->getId())));
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:Contrato:new.html.twig', array(
    			'cliente' => $cliente,
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($cliente)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$cliente = $em->getRepository('ParametrizarBundle:Cliente')->find($cliente);
    	
    	if (!$cliente) {
    		throw $this->createNotFoundException('El cliente solicitado no esta disponible.');
    	}
    	
    	$contrato = $em->getRepository('ParametrizarBundle:Contrato')->findBy(array('cliente' => $cliente));
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre());
    	
    	return $this->render('ParametrizarBundle:Cliente:show.html.twig', array(
    			'cliente'  => $cliente,
    			'contratos' => $contrato
    	));
    }
    
    public function editAction($contrato)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$contrato = $em->getRepository('ParametrizarBundle:Contrato')->find($contrato);
    
    	if (!$contrato) {
    		throw $this->createNotFoundException('El contrato solicitado no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Contrato");
    	$breadcrumbs->addItem("Modificar ");
    
    	$form = $this->createForm(new ContratoType(), $contrato);
    
    	return $this->render('ParametrizarBundle:Contrato:edit.html.twig', array(
    			'contrato' => $contrato,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($contrato)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$contrato = $em->getRepository('ParametrizarBundle:Contrato')->find($contrato);
    
    	if (!$contrato) {
    		throw $this->createNotFoundException('El contrato solicitado no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Contrato");
    	$breadcrumbs->addItem("Modificar ");
    
    	$form = $this->createForm(new ContratoType(), $contrato);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($contrato);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La contrato ha sido modificado éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('contrato_edit', array('contrato' => $contrato->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:Contrato:edit.html.twig', array(
    			'contrato' => $contrato,
    			'form' => $form->createView()
    	));
    }
}