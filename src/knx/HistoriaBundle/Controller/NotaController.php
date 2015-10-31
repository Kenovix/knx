<?php
namespace knx\HistoriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use knx\HistoriaBundle\Entity\Notas;
use knx\HistoriaBundle\Entity\Hc;

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
			
			// se optine el servicio para verificar si es de odontologia o de consulta
			$servicio = $factura->getServicio();			
			
			/* Si la historia no existe se procedera a crear una historia en code behind, despues de crear la
			 * historia se procede a visualizar el formulario de las notas. */
			if(!$historia){				
				
				$historia = $em->getRepository('HistoriaBundle:Notas')->createEmptyHc($factura);
			}
			
			if($servicio->getId() == '6'){
				// se redirecciona a el edit de la odontologia
				return $this->redirect($this->generateUrl('odontologia_edit',array("factura" => $factura->getId())));
			}else{
				// se redirecciona a el edit de la historia
				return $this->redirect($this->generateUrl('historia_edit',array("factura" => $factura->getId())));
			}			
		}		
		// envia un mesaje diciendo que la facuta no contiene los permisos suficientes para crear una historia clinica
		return $this->redirect($this->generateUrl('paciente_filtro'));		
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
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));
		
		$usuario = $this->get('security.context')->getToken()->getUser();
		
		
		foreach ($usuario->getRoles() as $role)
		{
			if($role == 'ROLE_MEDICO')
				return $this->redirect($this->generateUrl('historia_edit',array("factura" => $factura->getId())));
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Consltas en urgencias",$this->get("router")->generate("historia_urgencias_list"));
		$breadcrumbs->addItem("Nota Nueva");
		
		return $this->render('HistoriaBundle:Notas:new.html.twig',array(
				'factura' 	=> $factura,
				'paciente' 	=> $paciente,
				'historia' 	=> $historia,
				'today'		=> new \DateTime('now'),
				'usuario'  	=> $usuario,				
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
						
			$perfil = false;
			foreach ($usuario->getRoles() as $role)
			{
				if($role == 'ROLE_MEDICO')
					$perfil = true;
			}
			if($perfil)
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
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));

		// visualizacion del rastro de miga
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("nota_new", array('factura' => $factura->getId() )));
		$breadcrumbs->addItem("Listado",$this->get("router")->generate("nota_list",	array("historia" => $historia->getId())));
		$breadcrumbs->addItem("Detalle");

		return $this->render('HistoriaBundle:Notas:show.html.twig',array(
				'nota' => $nota,
				'listNotas' => $listNotas,
				'factura' => $factura,
				'paciente' => $paciente,
				'historia' => $historia,
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
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));

		$editForm = $this->createForm(new NotasType(), $nota);

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("nota_new", array('factura' => $factura->getId() )));
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
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("nota_new", array('factura' => $factura->getId() )));
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

		// se filtran las notas para los auxiliares "se visualizan las notas de cada auxiliar"
		$usuario = $this->get('security.context')->getToken()->getUser();
		$listNotas = $em->getRepository('HistoriaBundle:Notas')->findListAuxNotas($historia,$usuario);

		// se verifica q la nota exista
		if (!$listNotas) {
			
			$historia = $em->getRepository('HistoriaBundle:Hc')->find($historia);
			if(!$historia){
				$this->get('session')->setFlash('error','La historia clinica no existe.');
				return $this->redirect($this->generateUrl('paciente_filtro'));
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
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));

		// visualizacion del rastro de miga
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("nota_new", array('factura' => $factura->getId() )));
		$breadcrumbs->addItem("Listado");		

		// Se realiza la respectiva paginacion
		$paginator = $this->get('knp_paginator');
		$listNotas = $paginator->paginate($listNotas,$this->getRequest()->query->get('page', 1), 10);

		return $this->render('HistoriaBundle:Notas:list.html.twig',array(
				'listNotas'=> $listNotas,
				'factura'  => $factura,
				'paciente' => $paciente,
                                'historia' => $historia
			));
	}
        
        
        public function listmAction($historia) 
	{
		$em = $this->getDoctrine()->getEntityManager();

		// se filtran las notas para los auxiliares "se visualizan las notas de cada auxiliar"
		//$usuario = $this->get('security.context')->getToken()->getUser();
		//$listOrdenes = $em->getRepository('HistoriaBundle:Hc')->findListAuxNotas($historia,$usuario);

			
			$historia = $em->getRepository('HistoriaBundle:Hc')->find($historia);
			if(!$historia){
				$this->get('session')->setFlash('error','La historia clinica no existe.');
				return $this->redirect($this->generateUrl('paciente_filtro'));
			}
			$factura = $historia->getFactura();
			$this->get('session')->setFlash('error','La historia clinica seleccionada no tiene notas creadas, ingrese la informacion necesaria');
			return $this->redirect($this->generateUrl('nota_new',array('factura' => $factura->getId())));
		

		// se optiene la historia q corresponde a esa nota.
		//$orden = $listOrdenes[0];
		//$historia = $nota->getHc();

		
		// visualizacion del rastro de miga
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Notas",$this->get("router")->generate("nota_new", array('factura' => $factura->getId() )));
		$breadcrumbs->addItem("Listado");		

		// Se realiza la respectiva paginacion
		$paginator = $this->get('knp_paginator');
		$listNotas = $paginator->paginate($listNotas,$this->getRequest()->query->get('page', 1), 10);

		return $this->render('HistoriaBundle:Notas:listm.html.twig',array(
				'ordenes'=> $historia,
				'factura'  => $factura,
				'paciente' => $paciente,				
			));
	}
	
	public function printAction($historia)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$historia = $em->getRepository('HistoriaBundle:Hc')->find($historia);
		
		if (!$historia) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}
		$listNotas = $em->getRepository('HistoriaBundle:Notas')->findByHc($historia, array('fecha' => 'DESC'));
		$factura = $historia->getFactura();
		$paciente = $factura->getPaciente();
		$cliente = $factura->getCliente();
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));
		$depto = $em->getRepository('ParametrizarBundle:Depto')->find($paciente->getDepto());
		$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($paciente->getMupio());
		$afiliacion = $em->getRepository('ParametrizarBundle:Afiliacion')->findOneBy(array('cliente' => $cliente->getId(), 'paciente' => $paciente->getId()));
		
		// verificar que los servicios existan para evitar posibles errores ya q se usan los objetos en el impreso
		if($historia->getServiEgre()){
			$serviEgre = $em->getRepository('ParametrizarBundle:Servicio')->find($historia->getServiEgre());
		}else{
			$serviEgre="";
		}
		// si los servicios existen se asignan a la historia para manejarlos como objetos.
		$historia->setServiEgre($serviEgre);
		
		$pdf = $this->get('white_october.tcpdf')->create();
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('dejavusans', '', 8, '', true);
		
		// se establece el titulo de la impresion dependiendo el servicio de ingreso
		$tipoIngreso = $factura->getTipo();
		if( $tipoIngreso == 'U' or $tipoIngreso == 'H')
		{
			$titulo = "Historia Clinica Urgencias No.".$historia->getId();
		}elseif($tipoIngreso == 'C'){
			$titulo = "Historia Clinica Consulta Externa No.".$historia->getId();
		}
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(1);
		$pdf->SetFooterMargin(10);
		
		// set image scale factor
		$pdf->setImageScale(5);
		
		$pdf->AddPage();
		
		$header = $this->renderView('HistoriaBundle:Impresos:header.html.twig',array(
				'factura'  	 => $factura,
				'afiliacion' => $afiliacion,
				'paciente' 	 => $paciente,
				'historia' 	 => $historia,
				'depto'		 => $depto,
				'mupio'		 => $mupio,
				'titulo'	 => $titulo,
		));
		
		$notasHtml = $this->renderView('HistoriaBundle:Impresos:Notas.html.twig',array(
				'listNotas'  => $listNotas,
		));
		
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $notasHtml, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		
		$response = new Response($pdf->Output('HcPrint.pdf', 'I'));
		$response->headers->set('Content-Type', 'application/pdf');
	}
}