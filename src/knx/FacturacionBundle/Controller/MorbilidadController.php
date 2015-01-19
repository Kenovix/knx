<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

use knx\FacturacionBundle\Form\MorbilidadType;

class MorbilidadController extends Controller
{
	public function vistaAction()
	{
		$form = $this->createForm(new MorbilidadType());
		return $this->render('FacturacionBundle:Morbilidad:vista.html.twig',array(
				'form'   => $form->createView(),
		));
	}
	
	public function listAction()
	{
		$request = $this->getRequest();
		$form    = $this->createForm(new MorbilidadType());
		$form->bindRequest($request);
		
		if ($form->isValid()) 
		{
			// se optienen todos los datos del formulario para ser procesado de forma individual 
			
			$dateStart = date_create_from_format('d/m/Y',$form->get('dateStart')->getData());
			$dateEnd   = date_create_from_format('d/m/Y',$form->get('dateEnd')->getData());
			$atencion		= $form->get('atencion')->getData();
			$genero			= $form->get('genero')->getData();
			$oldStart		= $form->get('edadInicial')->getData();
			$oldEnd			= $form->get('edadFinal')->getData();
			$centroCostos	= $form->get('centroCostos')->getData();
			
			// se verifica que la informacion ingresada en el formulario sea valida
			if($dateStart > $dateEnd and is_numeric($oldStart) and is_numeric($oldEnd) and $oldEnd>$oldStart and $oldEnd<=100 and $oldStart>=0)
			{ 
				$this->get('session')->setFlash('error', 'Las Fechas o Las Edades No Son Correctas, Vuelva A Ingresar La Información.');
				return $this->redirect($this->generateUrl('morbilidad_vista'));
			}			

			// se realiza la respectiva consulta al repositorio
			$em = $this->getDoctrine()->getEntityManager();			
			$informeMorvilidad = $em->getRepository('FacturacionBundle:FacturaCargo')->findMorbilidad($atencion,$genero,$oldStart,$oldEnd,$centroCostos,$dateStart,$dateEnd);			
			
			
			$pdf = $this->instanciarImpreso("Informe de Morbilidad ");
			$view = $this->renderView('FacturacionBundle:Morbilidad:morbilidadPdf.html.twig',
					array(
							'dateStart' => 	$dateStart,
							'dateEnd' 	=> 	$dateEnd,  
							'atencion' 	=> 	$atencion,
							'genero' 	=> 	$genero,	
							'oldStart' 	=>	$oldStart,
							'oldEnd' 	=> 	$oldEnd,
							'servicio' 	=> 	$centroCostos,
							'informe' 	=>  $informeMorvilidad,
					));
			
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $view, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			
			$response = new Response($pdf->Output('Informe_servicio.pdf', 'I'));
			$response->headers->set('Content-Type', 'application/pdf');
			
		}else{
			$this->get('session')->setFlash('error', 'Opcion No Validad, Vuelva A Seleccionar Una Opcion.');
			return $this->redirect($this->generateUrl('morbilidad_vista'));
		}		
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
