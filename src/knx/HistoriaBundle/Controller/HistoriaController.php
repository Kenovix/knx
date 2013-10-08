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

		if($historia->getPFechaN())
		{
			$historia->setPFechaN($historia->getPFechaN()->format('d/m/Y H:i'));
		}
		if($historia->getPFechaM())
		{
			$historia->setPFechaM($historia->getPFechaM()->format('d/m/Y H:i'));
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
		
		// si el paciente no posee signos no dejara guardar ya que los signos son obligatorios para una hitoria
		$listNotas = $em->getRepository('HistoriaBundle:Notas')->findByHc($historia, array('fecha' => 'DESC'));
		if(!$listNotas){
			return $this->validarHistoria($factura, $historia, $form_historia);
		}
		
		// validacion del formulario del parto
		$dateTomorrow = new \DateTime('tomorrow');
		$dateYesterday = new \DateTime('yesterday');		
		
		// se valida que las fechas de nacimiento o de muerte no sean mayor o menor a 24 horas
		$pFechaN = date_create_from_format('d/m/Y H:i',$form_historia->get('pFechaN')->getData());
		if($pFechaN){
			if($pFechaN>$dateYesterday && $pFechaN<$dateTomorrow)
			{
				$historia->setPFechaN($pFechaN);
				if(!$form_historia->get('pEdadG')->getData() ||	!$form_historia->get('pControlP')->getData() || !$form_historia->get('pSexo')->getData() ||	!$form_historia->get('pPeso')->getData() || !$form_historia->get('pDx')->getData())
				{
					return $this->validarHistoria($factura, $historia, $form_historia);
				}
			}else{
				return $this->validarHistoria($factura, $historia, $form_historia);
			}								
		}		
		$pFechaM = date_create_from_format('d/m/Y H:i',$form_historia->get('pFechaM')->getData());
		if($pFechaM){
			if($pFechaM>$dateYesterday && $pFechaM<$dateTomorrow)
			{
				$historia->setPFechaM($pFechaM);
				if(!$form_historia->get('pCausaM')->getData())
				{
					return $this->validarHistoria($factura, $historia, $form_historia);
				}				
			}			
		}
		
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
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));
		
		// consulto la afiliacion ya que esta contiene el nivel y el rango del paciente con su cliente
		$cliente = $factura->getCliente();
		$afiliacion = $em->getRepository('ParametrizarBundle:Afiliacion')->findOneBy(array('cliente' => $cliente->getId(), 'paciente' => $paciente->getId()));
		
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
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Historia",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Modificar " . $paciente->getPriNombre());
		
		$this->get('session')->setFlash('info','Los campos * son obligatorios, los signos son obligatorios, valide que su información sea correcta para poder guardar.');
		
		// Visualizacion de la plantilla.
		return $this->render('HistoriaBundle:Historia:edit.html.twig',array(
				'factura'  	 => $factura,
				'afiliacion' => $afiliacion,
				'today'		 => new \DateTime('now'),  // fecha para el ingreso en algunos campos del formulario
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

	public function searchResultAction($paciente) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);
		
		if (!$paciente) {
			throw $this->createNotFoundException('El paciente solicitado no existe.');
		}				
		// se procede a realizar una consulta que trae todo el historial clinico del paciente
		$historia = $em->getRepository('HistoriaBundle:Hc')->findHistoriasPaciente($paciente);
		
		if (!$historia) {
			$this->get('session')->setFlash('error','El paciente no contiene ningun historial clinico.');
			return $this->redirect($this->generateUrl('paciente_filtro'));
		}
		$usuario = $this->get('security.context')->getToken()->getUser();

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Listado Historial Clinico");
				
		$perfil = null;
		foreach ($usuario->getRoles() as $role)
		{
			if($role == 'ROLE_MEDICO')
			{
				$perfil = $role;
			}			
		}
		
		
		return $this->render('HistoriaBundle:Historia:search.html.twig',array(
				'historia' => $historia,
				'paciente' => $paciente,
				'usuario'  => $usuario,
				'perfil'   => $perfil,
			));
	}
	
	public function listUrgenciasAction()
	{
		$paginator = $this->get('knp_paginator');
		$em = $this->getDoctrine()->getEntityManager();
		$urgencias = $em->getRepository('HistoriaBundle:Hc')->listHcUrgencias();
		$urgencias = $paginator->paginate($urgencias,$this->getRequest()->query->get('page', 1), 10);
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Urgencias");
		
		return $this->render('HistoriaBundle:Historia:urgencias.html.twig',array(
				'urgencias_hc' => $urgencias,
		));
	}

	public function listExternasAction()
	{
		$paginator = $this->get('knp_paginator');
		
		$profesional = $this->container->get('security.context')->getToken()->getUser();
		
		$em = $this->getDoctrine()->getEntityManager();
		$externas = $em->getRepository('HistoriaBundle:Hc')->listHcExternasPendientes($profesional->getId());
		
		$externas = $paginator->paginate($externas,$this->getRequest()->query->get('page', 1), 10);
	
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Consultas externas");
	
		return $this->render('HistoriaBundle:Historia:externas.html.twig',array(
				'externas_hc' => $externas,
		));
	}
	
	public function listUrgenciasPendientesAction()
	{
		$paginator = $this->get('knp_paginator');
	
		$em = $this->getDoctrine()->getEntityManager();
		$urgencias = $em->getRepository('HistoriaBundle:Hc')->listHcUrgenciasPendientes();
	
		$urgencias = $paginator->paginate($urgencias,$this->getRequest()->query->get('page', 1), 10);
	
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Consultas en urgencias");
	
		return $this->render('HistoriaBundle:Historia:urgencias_pendientes.html.twig',array(
				'urgencias_hc' => $urgencias,
		));
	}
}
