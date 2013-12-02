<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FacturacionBundle\Entity\FacturaCargo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

use knx\FacturacionBundle\Form\FacturaCargoType;

class FacturaCargoController extends Controller
{
	// como titulo el nombre del reporte a presentar con su respectiva fecha en que se genera asi mismo con la fecha
	// desde y hasta que se genera el cargo.

	//CodigoCargo, NombreCargo  cantidadesCargoPrestado, ValorUnitario y el CostoTotale

	public function informeCargoAction()
	{
		$form = $this->createForm(new FacturaCargoType());
		return $this->render('FacturacionBundle:FacturaCargo:new.html.twig',array(
    			'form'   => $form->createView(),
    	));
	}

	public function questionTipoCargoAction()
	{
		$request = $this->getRequest();
		$form    = $this->createForm(new FacturaCargoType());
		$form->bindRequest($request);

		if ($form->isValid()) {

			$option    = $form->get('opcion')->getData();
			$dateStart = date_create_from_format('d/m/Y',$form->get('dateStart')->getData());
			$dateEnd   = date_create_from_format('d/m/Y',$form->get('dateEnd')->getData());

			if($dateStart > $dateEnd )
			{
				$this->get('session')->setFlash('error', 'Las Fechas No Son Correctas, Vuelva A Ingresar La Información.');
				return $this->redirect($this->generateUrl('reporte_cargo_new'));
			}

			switch ($option)
			{
				case 'IG':					
					$cliente = $form->get('cliente')->getData();					
					$this->informeGeneral($dateStart,$dateEnd,$cliente);
					break;
				case 'IR':
					$regimen = $form->get('regimen')->getData();					
					$this->informeRegimen($dateStart,$dateEnd,$regimen);
					break;
				case 'IAR':
					$servicio = $form->get('servicio')->getData();
					$this->informeActividadRealizada($dateStart,$dateEnd,$servicio);
					break;
				case 'ICRM':
					$usuario = $form->get('usuarios')->getData();
					$this->informeConsultaMedicos($dateStart,$dateEnd,$usuario);
					break;
				case 'IRR':					
					$this->informeRemisionRealizada($dateStart,$dateEnd);
					break;
				case 'IM':					
					$this->informeMorbilida($dateStart,$dateEnd,$usuario);
					break;
				case 'BC':
					$this->boletinCierreMes($dateStart,$dateEnd);
					break;
					break;
				case 'IPS':
					$this->informePrestacionServicio($dateStart,$dateEnd);
					break;
			}

			$this->get('session')->setFlash('error', 'Opcion No Validad, Vuelva A Selepcionar Una Opcion.');
			return $this->redirect($this->generateUrl('reporte_cargo_new'));
		}
	}

	private function informeGeneral($dateStart,$dateEnd,$cliente)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		if ($cliente){
			$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeGeneralCliente($dateStart,$dateEnd,$cliente->getId());
		}else{
			$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeGeneral($dateStart,$dateEnd);
		}		
		
		$pdf = $this->instanciarImpreso("Informe General ");
		$view = $this->renderView('FacturacionBundle:Reportes:InformeGeneral.html.twig',
				array(
						'facturaCargo' => $facturaCargo,
						));

		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $view, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

		$response = new Response($pdf->Output('Informe_general.pdf', 'D'));
		$response->headers->set('Content-Type', 'application/pdf');
	}

	private function informeRegimen($dateStart,$dateEnd,$regimen)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		if($regimen){
			$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeTipoRegimen($dateStart,$dateEnd,$regimen);
		}else{
			$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeRegimen($dateStart,$dateEnd);
		}
		
		$pdf = $this->instanciarImpreso("Informe Por Regimen ");
		$view = $this->renderView('FacturacionBundle:Reportes:InformeRegimen.html.twig',
				array(
						'facturaCargo' => $facturaCargo,
				));

		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $view, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

		$response = new Response($pdf->Output('Informe_regimen.pdf', 'D'));
		$response->headers->set('Content-Type', 'application/pdf');
	}	
	
	private function informeActividadRealizada($dateStart,$dateEnd,$servicio)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		if($servicio){
			$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeTipoServicio($dateStart,$dateEnd,$servicio->getId());
		}else{
			$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeServicio($dateStart,$dateEnd);
		}
		
		$pdf = $this->instanciarImpreso("Informe Por Servicio ");		
		$view = $this->renderView('FacturacionBundle:Reportes:InformeServicio.html.twig',
				array(
						'facturaCargo' => $facturaCargo,
				));
		
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $view, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		
		$response = new Response($pdf->Output('Informe_servicio.pdf', 'D'));
		$response->headers->set('Content-Type', 'application/pdf');
	}
	
	private function informeConsultaMedicos($dateStart,$dateEnd,$usuario)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		if($usuario){
			$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeConsultaMedico($dateStart,$dateEnd,$usuario->getId());
		}else{
			$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeConsultasMedicos($dateStart,$dateEnd);
		}
		
		$pdf = $this->instanciarImpreso("Informe Constultas Medicas ");		
		$view = $this->renderView('FacturacionBundle:Reportes:InformeConsultaMedico.html.twig',
				array(
						'dateStart' => $dateStart,
						'dateEnd' => $dateEnd,
						'facturaCargo' => $facturaCargo,
				));
		
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $view, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		
		$response = new Response($pdf->Output('Informe_servicio.pdf', 'D'));
		$response->headers->set('Content-Type', 'application/pdf');
	}
	
	
	
	private function informeRemisionRealizada($dateStart,$dateEnd)
	{
		$em = $this->getDoctrine()->getEntityManager();		
		$facturaCargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findInformeRemisionRealizada($dateStart,$dateEnd);		
	
		$pdf = $this->instanciarImpreso("Informe Constultas Medicas ");
		$view = $this->renderView('FacturacionBundle:Reportes:InformeRemisionRealizada.html.twig',
				array(
						'dateStart' => $dateStart,
						'dateEnd' => $dateEnd,
						'facturaCargo' => $facturaCargo,
				));
	
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $view, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
	
		$response = new Response($pdf->Output('Informe_servicio.pdf', 'D'));
		$response->headers->set('Content-Type', 'application/pdf');
	}
	
	private function instanciarImpreso($title)
	{
		// se instancia el objeto del tcpdf
		$pdf = $this->get('white_october.tcpdf')->create();

		$pdf->setFontSubsetting(true);
		$pdf->SetFont('dejavusans', '', 8, '', true);

		// Header and footer
		//$pdf->SetHeaderData('logo.jpg', 20, 'Hospital San Agustin', $title);
		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
		$pdf->setHeaderFont(Array('dejavusans', '', 8));
		$pdf->setFooterFont(Array('dejavusans', '', 8));

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(1);
		$pdf->SetFooterMargin(10);

		// set image scale factor
		$pdf->setImageScale(5);
		$pdf->AddPage();

		return $pdf;
	}
}
