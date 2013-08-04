<?php
namespace knx\HistoriaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use knx\HistoriaBundle\Entity\Hc;
use knx\HistoriaBundle\Entity\Notas;

class ImpresionHistoriaController extends Controller
{
	public function printAction($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
	
		$usuario = $this->get('security.context')->getToken()->getUser();
		$historia = $factura->getHc();
		$paciente = $factura->getPaciente();
		$hc_cie = $em->getRepository('HistoriaBundle:Hc')->findHcDx($historia->getId());
		$hc_exa = $em->getRepository('HistoriaBundle:Hc')->findHcExamen($historia->getId());
		$hc_lab = $em->getRepository('HistoriaBundle:Hc')->findHcLabora($historia->getId());
		$depto = $em->getRepository('ParametrizarBundle:Depto')->find($paciente->getDepto());
		$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($paciente->getMupio());
		$listNotas = $em->getRepository('HistoriaBundle:Notas')->findByHc($historia, array('fecha' => 'DESC'));
	
	
		// verificar que los servicios existan para evitar posibles errores ya q se usan los objetos en el impreso		
		if($historia->getServiEgre()){
			$serviEgre = $em->getRepository('ParametrizarBundle:Servicio')->find($historia->getServiEgre());
		}else{
			$serviEgre="";
		}
		// si los servicios existen se asignan a la historia para manejarlos como objetos.		
		$historia->setServiEgre($serviEgre);		
		
		if($historia->getDxSalida())
		{
			$dxSalida = $em->getRepository('HistoriaBundle:Cie')->find($historia->getDxSalida());
			$historia->setDxSalida($dxSalida);
		}
	
		// para poder imprimir los signos del paciente se consultan todas las notas y se ordenan en DESC  y de esta lista se optiene el primer objeto
		if($listNotas)
		{
			$listNotas = $listNotas[0];
		}
	
		// se instancia el objeto del tcpdf
		$pdf = $this->get('white_october.tcpdf')->create();
	
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('dejavusans', '', 8, '', true);
	
		// Header and footer
		$pdf->SetHeaderData('logo.jpg', 20, 'Hospital San Agustin', 'Historia Clinica  No. '.$historia->getId());
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
	
		$header = $this->renderView('HistoriaBundle:Impresos:header.html.twig',array(
				'factura'  	 => $factura,
				'paciente' 	 => $paciente,
				'historia' 	 => $historia,
				'depto'		 => $depto,
				'mupio'		 => $mupio,
		));
	
		$body = $this->renderView('HistoriaBundle:Impresos:Body.html.twig',array(
				'factura'  	 => $factura,
				'usuario'  	 => $usuario,
				'historia' 	 => $historia,
				'hc_cie' 	 => $hc_cie,
				'hc_exa' 	 => $hc_exa,
				'hc_lab' 	 => $hc_lab,
				'listNota'	 => $listNotas,
		));
	
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $body, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
	
		// Se valida la iformacion que va en formato de media carta tal como la remision, incapacidad, examenes, procedimientos y medicamentos		
		
		if($historia->getIncapacidad() != '')
		{
			$incapacidad = $this->renderView('HistoriaBundle:Impresos:Incapacidad.html.twig',array(
					'historia' 	 => $historia,
					'usuario'  	 => $usuario,
			));
				
			$pdf->AddPage('P', 'A4');
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $incapacidad, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		}
		if($historia->getMedicamentosS() != '')
		{
			$medicamentos = $this->renderView('HistoriaBundle:Impresos:Medicamentos.html.twig',array(
					'historia' 	 => $historia,
					'usuario'  	 => $usuario,
			));
	
			$pdf->AddPage('P', 'A4');
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $medicamentos, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		}
		if($historia->getProcedimientosS() != '')
		{
			$procedimientos = $this->renderView('HistoriaBundle:Impresos:Procedimientos.html.twig',array(
					'historia' 	 => $historia,
					'usuario'  	 => $usuario,
			));
	
			$pdf->AddPage('P', 'A4');
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $procedimientos, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		}
		if($historia->getExamenesS() != '')
		{
			$examenes = $this->renderView('HistoriaBundle:Impresos:Examenes.html.twig',array(
					'historia' 	 => $historia,
					'usuario'  	 => $usuario,
			));
	
			$pdf->AddPage('P', 'A4');
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $examenes, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		}
	
	
		$response = new Response($pdf->Output('HcPrint.pdf', 'I'));
		$response->headers->set('Content-Type', 'application/pdf');
	}

}
