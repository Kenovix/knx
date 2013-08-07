<?php
namespace knx\HistoriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use knx\HistoriaBundle\Entity\Notas;
use knx\HistoriaBundle\Form\NotasType;

class NotaController extends Controller 
{
	public function validaHcRutaAction($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
		
		if (!$factura) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}
		
		$tipoIngreso = $factura->getTipo();
		if( $tipoIngreso == 'U' or $tipoIngreso == 'H')
		{
			// se redirecciona a crear una nueva nota para la historia clinica.			
			return $this->redirect($this->generateUrl('nota_new',array("factura" => $factura->getId())));
		}
		elseif($tipoIngreso == 'C')
		{
			// se crea automaticamente la historia y luego lo pasa al edit de la historia.
			$historia = $factura->getHc();
			/* Si la historia no existe se procedera a crear una historia en code behind, despues de crear la
			 * historia se procede a visualizar el formulario de las notas. */
			if(!$historia){				
				
				$historia = $em->getRepository('HistoriaBundle:Notas')->createEmptyHc($factura);
			}
			// se redirecciona la a la editccion de la historia
			return $this->redirect($this->generateUrl('historia_edit',array("factura" => $factura->getId())));
		}		
		// envia un mesaje diciendo que la facuta no contiene los permisos suficientes para crear una historia clinica
		return $this->redirect($this->generateUrl('historia_search'));		
	}
	
	public function newAction($factura) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);

		if (!$factura) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}

		$notas = new Notas();
		$notas->setFecha(new \DateTime('now'));
		$form = $this->createForm(new NotasType(), $notas);
		$historia = $factura->getHc();	

		return $this->validarNotas($form, $factura, $historia);
		
	}
	
	public function validarNotas($nota_form,$factura,$historia)
	{
		$paciente = $factura->getPaciente();
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Nueva");
		
		return $this->render('HistoriaBundle:Notas:new.html.twig',array(
				'factura' => $factura,
				'paciente' => $paciente,
				'historia' => $historia,
				'form' => $nota_form->createView(),
		));
	}

	public function saveAction($factura) 
	{			
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);

		if (!$factura) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}
		$historia = $factura->getHc();

		if(!$historia){
			/* Si la historia no existe se procedera a crear una historia en code behind, despues de crear la
			 * historia se procede a visualizar el formulario de las notas.
			 */
			$historia = $em->getRepository('HistoriaBundle:Notas')->createEmptyHc($factura);
		}
		$notas = new Notas();
		$request = $this->getRequest();
		$notas->setFecha(new \DateTime('now'));			
		$form = $this->createForm(new NotasType(), $notas);			
		$form->bindRequest($request);

		if($form->isValid()){

			$em = $this->getDoctrine()->getEntityManager();
			$usuario = $this->get('security.context')->getToken()->getUser();			

			$notas->setHc($historia);
			$notas->setResponsable($usuario);
			$em->persist($notas);
			$em->flush();

			$this->get('session')->setFlash('ok','La informacion de la respectiva nota ha sido creada éxitosamente.');

			if($usuario->getRoles() == "ROLE_MEDICO")
			{
				return $this->redirect($this->generateUrl('historia_edit',array("factura" => $factura->getId())));
			}else{
				return $this->redirect($this->generateUrl('nota_list',array("historia" => $historia->getId())));
			}			

		} else {
			return $this->validarNotas($form, $factura, $historia);
		}	
	}

	public function showAction($nota) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$nota = $em->getRepository('HistoriaBundle:Notas')->find($nota);

		// se verifica q la nota exista
		if (!$nota) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}

		// se optiene la historia q corresponde a esa nota.
		$historia = $nota->getHc();

		// se listan todas las notas q corresponden a esa historia.
		$listNotas = $em->getRepository('HistoriaBundle:Notas')->findByHc($historia->getId());

		// Se optienen los objetos necesarios para la nota "paciente".		
		$factura = $historia->getFactura();
		$paciente = $factura->getPaciente();

		// visualizacion del rastro de miga
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Listado",$this->get("router")->generate("nota_list",	array("historia" => $historia->getId())));
		$breadcrumbs->addItem("Detalle");

		return $this->render('HistoriaBundle:Notas:show.html.twig',array(
				'nota' => $nota,
				'listNotas' => $listNotas,
				'factura' => $factura,
				'paciente' => $paciente,
				));
	}

	public function editAction($nota)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$nota = $em->getRepository('HistoriaBundle:Notas')->find($nota);

		if (!$nota) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}

		$historia = $nota->getHc();
		$factura = $historia->getFactura();
		$paciente = $factura->getPaciente();

		$editForm = $this->createForm(new NotasType(), $nota);

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Listado",$this->get("router")->generate("nota_list",array("historia" => $historia->getId())));
		$breadcrumbs->addItem("Detalle",$this->get("router")->generate("nota_show",array("nota" => $nota->getId())));
		$breadcrumbs->addItem("Modificar Nota");

		return $this->render('HistoriaBundle:Notas:edit.html.twig',array(
			'nota' => $nota,
			'factura' => $factura,
			'paciente' => $paciente,
			'edit_form' => $editForm->createView(),				
		));
	}

	public function updateAction($nota) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$nota = $em->getRepository('HistoriaBundle:Notas')->find($nota);

		if (!$nota) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}

		$editForm = $this->createForm(new NotasType(), $nota);
		$request = $this->getRequest();
		$editForm->bindRequest($request);

		if ($editForm->isValid()) {

			$em->persist($nota);
			$em->flush();

			$this->get('session')->setFlash('ok','La informacion ha sido modificada éxitosamente.');
			return $this->redirect($this->generateUrl('nota_edit',array('nota' => $nota->getId())));
		}

		$historia = $nota->getHc();
		$factura = $historia->getFactura();
		$paciente = $factura->getPaciente();

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Listado",$this->get("router")->generate("nota_list",array("historia" => $historia->getId())));
		$breadcrumbs->addItem("Detalle",$this->get("router")->generate("nota_show",array("nota" => $nota->getId())));
		$breadcrumbs->addItem("Modificar Nota");

		$this->get('session')->setFlash('error','La informacion no es correcta, verifique dichos datos.');

		return $this->render('HistoriaBundle:Notas:edit.html.twig',array(
				'nota' => $nota,
				'factura' => $factura,
				'paciente' => $paciente,
				'edit_form' => $editForm->createView(),
			));
	}

	public function listAction($historia) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$listNotas = $em->getRepository('HistoriaBundle:Notas')->findByHc($historia, array('fecha' => 'DESC'));

		// se verifica q la nota exista
		if (!$listNotas) {
			
			$historia = $em->getRepository('HistoriaBundle:Hc')->find($historia);
			if(!$historia){
				$this->get('session')->setFlash('error','La historia clinica no existe.');
				return $this->redirect($this->generateUrl('historia_search'));
			}
			$factura = $historia->getFactura();
			$this->get('session')->setFlash('error','La historia clinica seleccionada no tiene notas creadas, ingrese la informacion necesaria');
			return $this->redirect($this->generateUrl('nota_new',array('factura' => $factura->getId())));
		}

		// se optiene la historia q corresponde a esa nota.
		$nota = $listNotas[0];
		$historia = $nota->getHc();

		// Se optienen los objetos necesarios para la nota "paciente".
		$factura = $historia->getFactura();
		$paciente = $factura->getPaciente();

		// visualizacion del rastro de miga
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Listado");
		
		// se optione el role del usuario.
		$usuario = $this->get('security.context')->getToken()->getUser();

		// Se realiza la respectiva paginacion
		$paginator = $this->get('knp_paginator');
		$listNotas = $paginator->paginate($listNotas,$this->getRequest()->query->get('page', 1), 10);

		return $this->render('HistoriaBundle:Notas:list.html.twig',array(
				'listNotas'=> $listNotas,
				'factura'  => $factura,
				'paciente' => $paciente,
				'usuario'  => $usuario,
			));
	}
}
