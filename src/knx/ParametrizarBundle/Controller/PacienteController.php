<?php
namespace knx\ParametrizarBundle\Controller;

use knx\ParametrizarBundle\Form\PacienteType;
use knx\ParametrizarBundle\Entity\Paciente;
use knx\ParametrizarBundle\Form\AfiliacionType;
use knx\ParametrizarBundle\Entity\Afiliacion;
use knx\ParametrizarBundle\Form\pacienteSearchType;

use Symfony\Component\BrowserKit\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class PacienteController extends Controller
{
	public function newAction()
	{
		$entity = new Paciente();
		$form = $this->createForm(new PacienteType(), $entity);
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Nuevo");
	
		return $this->render('ParametrizarBundle:Paciente:new.html.twig',array(
				'form' => $form->createView()
		));
	}
	
	public function listAction($char)
	{
		$em = $this->getDoctrine()->getEntityManager();
		 
		$dql = $em->createQuery("SELECT p FROM ParametrizarBundle:Paciente p
						WHERE p.priNombre LIKE :nombre ORDER BY p.priNombre, p.priApellido ASC");
		 
		$dql->setParameter('nombre', $char.'%');
		$pacientes = $dql->getResult();
		
		$paginator = $this->get('knp_paginator');
		$pacientes = $paginator->paginate($pacientes, $this->getRequest()->query->get('page', 1),15);		 
	
		if(!$pacientes)
		{
			throw $this->createNotFoundException('La informacion solicitada no existe');
		}	
		 
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		
		return $this->render('ParametrizarBundle:Paciente:list.html.twig', array(
				'pacientes'  => $pacientes,
				'char' => $char,
		));
	}
	
	public function saveAction()
	{
		$paciente  = new Paciente();
		$request = $this->getRequest();		
		
		$form    = $this->createForm(new PacienteType(), $paciente);
		$form->bindRequest($request);
		
		if ($form->isValid()) {
			
			// se optinen los objetos mupio y depto para agregar su respectivo id
			$depto = $form->get('depto')->getData();
			$mupio = $form->get('mupio')->getData();			
			
			$paciente->setMupio($mupio->getId());
			$paciente->setDepto($depto->getId());
						
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($paciente);
			$em->flush();
	
			$this->get('session')->setFlash('info', 'El paciente ha sido creado éxitosamente.');
	
			return $this->redirect($this->generateUrl('paciente_show', array("paciente" => $paciente->getId())));	
		}
	
		return $this->render('ParametrizarBundle:Paciente:new.html.twig', array(				
				'form'   => $form->createView()
		));
	
	}
	
	public function showAction($paciente)
	{
		$em = $this->getDoctrine()->getEntityManager();	
		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);
	
		if (!$paciente) {
			throw $this->createNotFoundException('El paciente solicitado no existe.');
		}
		
		$depto = $em->getRepository('ParametrizarBundle:Depto')->find($paciente->getDepto());
		$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($paciente->getMupio());		
		$paciente->setDepto($depto);
		$paciente->setMupio($mupio);
	
		$afiliaciones = $em->getRepository('ParametrizarBundle:Afiliacion')->findByPaciente($paciente);
			
		$afiliacion = new Afiliacion();	
		$form = $this->createForm(new AfiliacionType(), $afiliacion);
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Detalles", $this->get("router")->generate("paciente_show", array("paciente" => $paciente->getId() )));
		
		 
		return $this->render('ParametrizarBundle:Paciente:show.html.twig', array(
					'paciente' => $paciente,
		  			'afiliaciones' => $afiliaciones,
		  			'form' => $form->createView()
				));
	}
	
	public function editAction($paciente)
	{
		$em = $this->getDoctrine()->getEntityManager();	
		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);	
	
		if (!$paciente) {
			throw $this->createNotFoundException('El paciente solicitado no existe');
		}		
		
		$depto = $em->getRepository('ParametrizarBundle:Depto')->find($paciente->getDepto());
		$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($paciente->getMupio());
		
		$paciente->setDepto($depto);				
		$paciente->setMupio($mupio);
	
		$editForm = $this->createForm(new PacienteType(), $paciente);	
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Detalles", $this->get("router")->generate("paciente_show", array("paciente" => $paciente->getId() )));
		$breadcrumbs->addItem("Edit");
	
		return $this->render('ParametrizarBundle:Paciente:edit.html.twig', array(
				'paciente' 	  => $paciente,								
				'edit_form'   => $editForm->createView()
		));
	}
	
	public function updateAction($paciente)
	{
		$em = $this->getDoctrine()->getEntityManager();	
		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);
	
		if (!$paciente) {
			throw $this->createNotFoundException('El paciente solicitado no existe.');
		}
	
		$editForm   = $this->createForm(new PacienteType(), $paciente);
		$request = $this->getRequest();
		$editForm->bindRequest($request);
	
		if ($editForm->isValid()) {
			
			// se optinen los objetos mupio y depto para agregar su respectivo id				
			$paciente->setMupio($paciente->getMupio()->getId());
			$paciente->setDepto($paciente->getDepto()->getId());
	
			$em->persist($paciente);
			$em->flush();
	
			$this->get('session')->setFlash('info', 'La información del paciente ha sido modificada éxitosamente.');
	
			return $this->redirect($this->generateUrl('paciente_edit', array('paciente' => $paciente->getId())));
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Detalles", $this->get("router")->generate("paciente_show", array("paciente" => $paciente->getId() )));
		$breadcrumbs->addItem("Edit");
	
		return $this->render('ParametrizarBundle:Paciente:edit.html.twig', array(
				'paciente'    => $paciente,
				'edit_form'   => $editForm->createView(),
		));
	}	
	
	public function filtrarAction()
	{
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$form   = $this->createForm(new pacienteSearchType());
		 
		if ($request->getMethod() == 'POST') {
	
			$form->bindRequest($request);
			 
			$tipoid = $form->get('tipoid')->getData();
			$id = $form->get('identificacion')->getData();
			$pri_nombre = $form->get('pri_nombre')->getData();
			$seg_nombre = $form->get('seg_nombre')->getData();
			$pri_apellido = $form->get('pri_apellido')->getData();
			$seg_apellido = $form->get('seg_apellido')->getData();
	
			$session->set('tipoid', $tipoid);
			$session->set('identificacion', $id);
			$session->set('pri_nombre', $pri_nombre);
			$session->set('seg_nombre', $seg_nombre);
			$session->set('pri_apellido', $pri_apellido);
			$session->set('seg_apellido', $seg_apellido);
	
		}else{
			$tipoid = $session->get('tipoid');
			$id = $session->get('identificacion');
			$pri_nombre = $session->get('pri_nombre');
			$seg_nombre = $session->get('seg_nombre');
			$pri_apellido = $session->get('pri_apellido');
			$seg_apellido = $session->get('seg_apellido');
		}
	
		$em = $this->getDoctrine()->getEntityManager();	
	
		/*$form->get('tipoid')->setData($tipoid);
		$form->get('identificacion')->setData($id);
		$form->get('pri_nombre')->setData($pri_nombre);
		$form->get('seg_nombre')->setData($seg_nombre);
		$form->get('pri_apellido')->setData($pri_apellido);
		$form->get('seg_apellido')->setData($seg_apellido);*/
	
		$boolean = 0;
		$query = "SELECT p FROM ParametrizarBundle:Paciente p WHERE ";
		$parametros = array();
		 
		if($tipoid){
			$query .= "p.tipoId = :tipoid AND ";
			$parametros["tipoid"] = $tipoid;
		}else{
			$boolean ++;
		}
	
		if($id){
			$query .= "p.identificacion LIKE :id AND ";
			$parametros["id"] = $id.'%';
		}else{
			$boolean ++;
		}
		 
		if($pri_nombre){
			$query .= "p.priNombre LIKE :priNombre AND ";
			$parametros["priNombre"] = $pri_nombre.'%';
		}else{
			$boolean ++;
		}
		 
		if($seg_nombre){
			$query .= "p.segNombre LIKE :segNombre AND ";
			$parametros["segNombre"] = $seg_nombre.'%';
		}else{
			$boolean ++;
		}
		 
		if($pri_apellido){
			$query .= "p.priApellido LIKE :priApellido AND ";
			$parametros["priApellido"] = $pri_apellido.'%';
		}else{
			$boolean ++;
		}
		 
		if($seg_apellido){
			$query .= "p.segApellido LIKE :segApellido AND ";
			$parametros["segApellido"] = $seg_apellido.'%';
		}else{
			$boolean ++;
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));		
		$breadcrumbs->addItem("Buscar");
		 
		if($boolean == 6){
			
			$this->get('session')->setFlash('info', 'Ingrese la información correspondiente para realizar la busqueda.');
			
			return $this->render('ParametrizarBundle:Paciente:filtro.html.twig', array(
					'entities'  => null,
					'form'   => $form->createView()
			));
		}
		
		$query = substr($query, 0, strlen($query)-4);		 
		$query .= " ORDER BY p.priNombre, p.priApellido ASC";		 
		$dql = $em->createQuery($query);		
			
		$dql->setParameters($parametros);			
		$pacientes = $dql->getResult();		
		$paginator = $this->get('knp_paginator');
		$pacientes = $paginator->paginate($pacientes, $this->getRequest()->query->get('page', 1),15);
		
		
	
		return $this->render('ParametrizarBundle:Paciente:filtro.html.twig', array(
				'entities'  => $pacientes,
				'form'   => $form->createView()
		));
	}
	
	public function findDeptoAction()
	{
		$request = $this->get('request');
		$depto = $request->request->get('depto');
	
		if (is_numeric($depto)) {
				
			$em = $this->getDoctrine()->getEntityManager();
			$depto = $em->getRepository('ParametrizarBundle:Depto')->find($depto);
				
			if(!$depto)
			{
				throw $this->createNotFoundException('La informacion solicitada no existe');
			}
	
			$query = $em->createQuery('SELECT m FROM ParametrizarBundle:Mupio m WHERE m.depto = :depto ORDER BY m.municipio ASC ');
			$query->setParameter('depto', $depto->getId());
			$mupio = $query->getArrayResult();
	
			if ($mupio) {
					
				$response = array("responseCode" => 200);
	
				foreach ($mupio as $key => $value) {
					$response['mupio'][$key] = $value;
				}
	
			} else {
				$response = array("responseCode" => 400,"msg" => "No hay informacion para la opción seleccionada");
			}
			$return = json_encode($response);
			return new Response($return, 200,array('Content-Type' => 'application/json'));
		}
	}
}
