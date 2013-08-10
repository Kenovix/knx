<?php
namespace knx\HistoriaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use knx\HistoriaBundle\Entity\Hc;
use knx\HistoriaBundle\Form\HcType;
use knx\HistoriaBundle\Entity\Notas;
use knx\HistoriaBundle\Form\NotasType;
use knx\HistoriaBundle\Form\SearchPacienteType;

class HistoriaController extends Controller 
{
	public function editAction($factura) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
		$historia = $factura->getHc();       

		/* No se verifica la existencia del paciente y los servicios porque si existe la factura existe el paciente
		 * y si existe la historia existen los servicios.
		 */
		if (!$factura || !$historia) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}

		if ($historia->getServiEgre()) {
			$serviEgre = $em->getRepository('ParametrizarBundle:Servicio')->find($historia->getServiEgre());
		} else {
			$serviEgre = "";
		}


		if($historia->getDxSalida())
		{
			$dxSalida = $em->getRepository('HistoriaBundle:Cie')->find($historia->getDxSalida());
		}else{
			$dxSalida = "";
		}		
	
		// se cargan los respectivos objetos para que el formulario los visualice correctamente.
		$historia->setServiEgre($serviEgre);
		$historia->setDxSalida($dxSalida);
		$historia->setFechaEgre(new \DateTime('now'));		
		$form_historia = $this->createForm(new HcType(), $historia);		
		
		return $this->validarHistoria($factura, $historia, $form_historia);		
	}

	public function updateAction($factura) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
		$historia = $factura->getHc();		

		if (!$factura || !$historia) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}

		$form_historia = $this->createForm(new HcType(), $historia);
		$request = $this->getRequest();
		$form_historia->bindRequest($request);

		if($form_historia->isValid()) 
		{
			
			/* Para el ingreso de los sevicios se trabaja con los IDs mas no con sus objetos ya q la informacion
			 * que se almacena en la historia no son relaciones, pero el formulario si trabaja con objetos.
			 */ 
			if($historia->getDxSalida()){
				$historia->setDxSalida($historia->getDxSalida()->getId());
			}
			if($historia->getServiEgre()){
				$historia->setServiEgre($historia->getServiEgre()->getId());				
			}		

			$historia->setFactura($factura);			
			$em->persist($historia);
			$em->flush();		

			$this->get('session')->setFlash('ok','La historia clinica ha sido modificada éxitosamente.');
			return $this->redirect($this->generateUrl('historia_edit',array('factura' => $factura->getId())));
		}
		
		return $this->validarHistoria($factura, $historia, $form_historia);
	}
	
	// esta funcion se ha creado para que la usen dos metodos y evitar la reescritura de la misma en ambos metodos.
	// los metodos q la usan son el update y el edit de la historia.
	public function validarHistoria($factura,$historia,$form_historia)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		// se optione el role del usuario.
		$usuario = $this->get('security.context')->getToken()->getUser();
		
		/* Se consultan los respectivos objetos q se trabajan en la historia todo por medio de la relacion
		 * OneToOne que tiene la hisotia y la factura.		*/
		$paciente = $factura->getPaciente();
		
		// Se realizan las respectivas consultas a sus respectivos repositorios para traer
		// la informacion correspondiente que se visualizara en la historia
		$paginator = $this->get('knp_paginator');
		$hc_cie = $em->getRepository('HistoriaBundle:Hc')->findHcDx($historia->getId());
		$hc_exa = $em->getRepository('HistoriaBundle:Hc')->findHcExamen($historia->getId());
		$hc_lab = $em->getRepository('HistoriaBundle:Hc')->findHcLabora($historia->getId());
		$hc_exa_all = $em->getRepository('HistoriaBundle:Examen')->findListAllExaHc($paciente->getId());
		
		// Visualizando las ultimas 10 notas de enfermeria realizdas a esta historia
		$listNotas = $em->getRepository('HistoriaBundle:Notas')->findByHc($historia, array('fecha' => 'DESC'));
		$listNotas = $paginator->paginate($listNotas,$this->getRequest()->query->get('page', 1), 10);
		
		// visualizo los ultimos 10 examenes del paciente en orden de fecha_r
		$hc_exa_all = $paginator->paginate($hc_exa_all,$this->getRequest()->query->get('page', 1), 10);
				
		$notas = new Notas();
		$notas->setFecha(new \DateTime('now'));//-----------------
		$form_nota = $this->createForm(new NotasType(), $notas);
		
		// rastro de miga
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Historia",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Modificar " . $paciente->getPriNombre());
		
		// Visualizacion de la plantilla.
		return $this->render('HistoriaBundle:Historia:edit.html.twig',array(
				'factura'  	 => $factura,
				'paciente' 	 => $paciente,
				'usuario'  	 => $usuario,
				'historia' 	 => $historia,
				'hc_cie' 	 => $hc_cie,
				'hc_exa' 	 => $hc_exa,
				'hc_exa_all' => $hc_exa_all,
				'listNotas'  => $listNotas,
				'hc_lab' 	 => $hc_lab,				
				'form_nota'  => $form_nota->createView(),
				'edit_form'  => $form_historia->createView(),
		));		
	}

	public function searchAction() {
		$form = $this->createForm(new SearchPacienteType());

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Busqueda rapida");

		/*
		 * Se agregan algunos atributos a la vista de tipo null porque esta plantilla la usan
		 * dos metodos los cuales contienen diferente informacion.
		 */
		return $this
				->render('HistoriaBundle:Historia:search.html.twig',array(
				'historia' => null,
				'paciente' => null,
				'search_form' => $form->createView(),
			));
	}

	public function searchResultAction() 
	{
		$request = $this->getRequest();
		$form = $this->createForm(new SearchPacienteType());
		$form->bindRequest($request);

		/* Se intancian algunos atributos de tipo null porque a estos se les asignara su respectiva
		 * informacion despues de confirmar que el formulario este correcto y que la historia exista.		 
		 */ 
		$historia = null;
		$paciente = null;

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Busqueda rapida");

		if ($form->isValid()) {
			$identifi = $form->get('cedula')->getData();
			$tipoid = $form->get('tipoid')->getData();

			$em = $this->getDoctrine()->getEntityManager();
			$historia = $em->getRepository('HistoriaBundle:Hc')->findHistoriasPaciente($identifi, $tipoid);

			if (!$historia) {
				$this->get('session')->setFlash('error','Busquedad no exítosa, vuelva a intentar.');
				return $this->redirect($this->generateUrl('historia_search'));
			}

			/* Si por lo menos una historia clinica existe entonces necesitaremos la informacion del paciente
			 * pero como la anterior consulta devuelve un array con objetos entonces procedemos a tomar un objeto de 
			 * esa lista de historias para llamar el objeto de la facutra y con este el objeto del paciente teniendo 
			 * en cuenta sus respectivas relaciones de historia OneToOne factura y paciente ManyToOne factura.
			 */
			$object = $historia[0];
			$paciente = $object->getFactura()->getPaciente();

		}

		return $this->render('HistoriaBundle:Historia:search.html.twig',array(
				'historia' => $historia,
				'paciente' => $paciente,
				'search_form' => $form->createView(),
			));
	}
	
	public function listUrgenciasAction()
	{
		$paginator = $this->get('knp_paginator');
		$em = $this->getDoctrine()->getEntityManager();
		$urgencias = $em->getRepository('HistoriaBundle:Hc')->listHcUrgencias();
		$urgencias = $paginator->paginate($urgencias,$this->getRequest()->query->get('page', 1), 10);
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("historia_search"));
		$breadcrumbs->addItem("Urgencias");
		
		return $this->render('HistoriaBundle:Historia:urgencias.html.twig',array(
				'urgencias_hc' => $urgencias,
		));		
	}	
}
