<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\Cliente;
use knx\ParametrizarBundle\Form\ClienteType;
use Symfony\Component\HttpFoundation\Session\Session;

class ClienteController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $cliente = $em->getRepository('ParametrizarBundle:Cliente')->findAll();
        
        return $this->render('ParametrizarBundle:Cliente:list.html.twig', array(
                'clientes'  => $cliente
        ));
    }

    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$cliente = new Cliente();
    	$form   = $this->createForm(new ClienteType(), $cliente);
    	
    	$em = $this->getDoctrine()->getEntityManager();

    	return $this->render('ParametrizarBundle:Cliente:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$request = $this->getRequest();
    	
    	$cliente = new Cliente();    	
    	$form = $this->createForm(new ClienteType(), $cliente);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {	    		
	    		 
	    		$em = $this->getDoctrine()->getEntityManager();
	    		
	    		$em->persist($cliente);
	    		$em->flush();
	    
	    		$this->get('session')->setFlash('ok', 'El cliente ha sido creado éxitosamente.');
	    
	    		return $this->redirect($this->generateUrl('cliente_show', array("cliente" => $cliente->getId())));
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:Cliente:new.html.twig', array(
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
    	
    	$procedimientos = $em->getRepository('ParametrizarBundle:Contrato')->findBy(array('cliente' => $cliente, 'tipo' => 'P'));
    	
    	$farmacos = $em->getRepository('ParametrizarBundle:Contrato')->findBy(array('cliente' => $cliente, 'tipo' => 'M'));
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre());
    	
    	return $this->render('ParametrizarBundle:Cliente:show.html.twig', array(
    			'cliente'  => $cliente,
    			'procedimientos' => $procedimientos,
    			'farmacos' => $farmacos
    	));
    }
    
    public function editAction($cliente)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$cliente = $em->getRepository('ParametrizarBundle:Cliente')->find($cliente);
    
    	if (!$cliente) {
    		throw $this->createNotFoundException('El cliente solicitado no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array("cliente" => $cliente->getId())));
    	$breadcrumbs->addItem("Modificar");
    
    	$form = $this->createForm(new ClienteType(), $cliente);
    
    	return $this->render('ParametrizarBundle:Cliente:edit.html.twig', array(
    			'cliente' => $cliente,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($cliente)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$cliente = $em->getRepository('ParametrizarBundle:Cliente')->find($cliente);
    
    	if (!$cliente) {
    		throw $this->createNotFoundException('La cliente solicitada no existe.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cliente", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array("cliente" => $cliente->getId())));
    	$breadcrumbs->addItem("Modificar ".$cliente->getNombre());
    
    	$form = $this->createForm(new ClienteType(), $cliente);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($cliente);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La cliente ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('cliente_edit', array('cliente' => $cliente->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:Cliente:edit.html.twig', array(
    			'cliente' => $cliente,
    			'form' => $form->createView()
    	));
    }
}