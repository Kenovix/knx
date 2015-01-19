<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\Contrato;
use knx\ParametrizarBundle\Form\ContratoType;
use Symfony\Component\HttpFoundation\Response;

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
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
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
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
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
    
    public function showAction($contrato)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$contrato = $em->getRepository('ParametrizarBundle:Contrato')->find($contrato);
    	
    	if (!$contrato) {
    		throw $this->createNotFoundException('El contrato solicitado no esta disponible.');
    	}
    	
    	$cliente = $contrato->getCliente();
    	
    	if($contrato->getTipo() == 'PP' || $contrato->getTipo() == 'P' ){
    		$contratado = $em->getRepository('ParametrizarBundle:ContratoCargo')->findBy(array('contrato' => $contrato->getId()));
    	}else{
    		$contratado = $em->getRepository('ParametrizarBundle:ImvContrato')->findBy(array('contrato' => $contrato->getId()));
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array('cliente' => $cliente->getId())));
    	$breadcrumbs->addItem('Contrato '.$contrato->getNumero());

    	return $this->render('ParametrizarBundle:Contrato:show.html.twig', array(
    			'cliente' => $cliente,
    			'contrato' => $contrato,
    			'contratado' => $contratado
    	));
    }
    
    public function editAction($contrato)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$contrato = $em->getRepository('ParametrizarBundle:Contrato')->find($contrato);
    
    	if (!$contrato) {
    		throw $this->createNotFoundException('El contrato solicitado no existe');
    	}
    	
    	$cliente = $contrato->getCliente();
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Clientes", $this->get("router")->generate("cliente_list"));
    	$breadcrumbs->addItem($cliente->getNombre(), $this->get("router")->generate("cliente_show", array('cliente' => $cliente->getId())));
    	$breadcrumbs->addItem('Contrato '.$contrato->getNumero(), $this->get("router")->generate("contrato_show", array('contrato' => $contrato->getId())));
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
    
    
    /**
     * @uses Función que consulta la tarifa pactada para las actividades de pyp.
     *
     * @param ninguno
     */
    public function jxBuscarTarifaPypAction() {
    
    	$request = $this->get('request');
    	 
    	$cliente = $request->request->get('cliente');
    	$cargo = $request->request->get('cargo');
    		 
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$dql = $em->createQuery("SELECT 
    								c
    							FROM
    								knx\ParametrizarBundle\Entity\Contrato c    									
    							WHERE
    								c.cliente = :cliente AND
    								c.fechaInicio <= :fecha AND
    								c.fechaFin >= :fecha AND
    								c.tipo = 'P' AND
    								c.estado = 'A'");
    	
    	$hoy = new \DateTime('now');
    	
    	$dql->setParameter("cliente", $cliente);
    	$dql->setParameter("fecha", $hoy);
    	
    	$contrato = $dql->getOneOrNullResult();
    		 
    	if($contrato){
    			
    		$cargo = $em->getRepository('ParametrizarBundle:Cargo')->find($cargo);
    		
    		if ($cargo) {
    			
    			$precio = ($cargo->getValor() + ($cargo->getValor() * $contrato->getPorcentaje()));
    			
    			$response=array("responseCode" => 200, "precio" => $precio);
    		}
    		else{
    			$response=array("responseCode"=>400, "msg"=>"La actividad solicitada no se encuentra parametrizada en el sistema");
    		}
    	}else{
    		$response=array("responseCode"=>400, "msg"=>"No hay contrato vigente para la actividad.");
    	}
    	 
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
}