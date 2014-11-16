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
	private $usuario;
	private $paciente;
	private $historia;
	private $afiliacion;
	private $cliente;
	private $hc_cie;
	private $hc_exa;
	private $hc_lab;
	private $depto;
	private $mupio;
	private $listNotas;
	
	public function printAction($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
		
		if(!$factura){
			throw $this->createNotFoundException('Error!! La información solicitada es incorrecta.');
		}

		//$this->setUsuario($this->get('security.context')->getToken()->getUser());
		$this->setUsuario($em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional()));
		$this->setHistoria($factura->getHc());
		$this->setPaciente($factura->getPaciente());
		$this->setCliente($factura->getCliente());
		$this->setAfiliacion($em->getRepository('ParametrizarBundle:Afiliacion')->findOneBy(array('cliente' => $this->getCliente()->getId(), 'paciente' => $this->getPaciente()->getId())));
		$this->setHcCie($em->getRepository('HistoriaBundle:Hc')->findHcDx($this->getHistoria()->getId()));
		$this->setHcExa($em->getRepository('HistoriaBundle:Hc')->findHcExamen($this->getHistoria()->getId()));
		$this->setHcLab($em->getRepository('HistoriaBundle:Hc')->findHcLabora($this->getHistoria()->getId()));
		$this->setListNotas($em->getRepository('HistoriaBundle:Notas')->findByHc($this->getHistoria(), array('fecha' => 'DESC')));
		$this->setMupio($em->getRepository('ParametrizarBundle:Mupio')->find($this->getPaciente()->getMupio()));
		$this->setDepto($em->getRepository('ParametrizarBundle:Depto')->find($this->getPaciente()->getDepto()));
		
		$this->printDocuments($factura);
	}
	
	public function printDocuments($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();		
		
		$usuario = $this->getUsuario();		
		$historia = $this->getHistoria();
		$paciente = $this->getPaciente();
		$cliente = $this->getCliente();
		$afiliacion = $this->getAfiliacion();
		$hc_cie = $this->getHcCie();
		$hc_exa = $this->getHcExa();
		$hc_lab = $this->getHcLab();
		$depto = $this->getDepto();
		$mupio = $this->getMupio();
		$listNotas = $this->getListNotas();
		
		
		// se establece el titulo de la impresion dependiendo el servicio de ingreso
		$tipoIngreso = $factura->getTipo();
		if( $tipoIngreso == 'U' or $tipoIngreso == 'H')
		{
			$titulo = "Historia Clinica Urgencias No.";
		}elseif($tipoIngreso == 'C'){
			$titulo = "Historia Clinica Consulta Externa No.";
		}
		
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
		}else{
			throw $this->createNotFoundException('Error!! Usted aun no ah tomado los signos,
					 recuerde primero guardar la información de la historia y posteriormente proceda a generar el impreso,
					 si no toma, guarda, o verifica los signos no podra generar el impreso.');
		}
		
		// se instancia el tcpdBundle
		$pdf = $this->getPritTcpd();
		
		// se organiza la informacion de las evoluciones, medicamentos, examenes y procedimientos para que estos sean impresos con un slto de renglon.
		// Evolucion.
		$string = $historia->getEvolucion();
		$newString = str_replace('::', '<br/>', $string);
		$historia->setEvolucion($newString);
		// Examenes.
		$string = $historia->getExamenesS();
		$newString = str_replace('::', '<br/>', $string);
		$historia->setExamenesS($newString);
		// Procedimientos.
		$string = $historia->getProcedimientosS();
		$newString = str_replace('::', '<br/>', $string);
		$historia->setProcedimientosS($newString);
		// Medicamentos
		$string = $historia->getMedicamentosS();
		$newString = str_replace('::', '<br/>', $string);
		$historia->setMedicamentosS($newString);
		
		
		// se genera el encabezado
		$header = $this->getHeader($factura,$titulo);
		
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
						
		if( $tipoIngreso == 'U' or $tipoIngreso == 'H')
		{
			// se genera el encabezado de la historia de la epicrisis
			$epicrisis = $this->getHeader($factura,"EPICRISIS URGENCIAS No ");
			$pdf->AddPage('P', 'A4');
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $epicrisis, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $body, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		}
		
		if($historia->getDestino() == '4')
		{
			// se genera el encabezado de la historia de la REMISION
			$remision = $this->getHeader($factura,"REMISION HISTORIA No ");
			$pdf->AddPage('P', 'A4');
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $remision, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $body, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		}	
		
		if($historia->getIncapacidad() != '')
		{
			$incapacidad = $this->renderView('HistoriaBundle:Impresos:Incapacidad.html.twig',array(
					'factura'        => $factura,
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
					'factura'        => $factura,
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
					'factura'        => $factura,
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
					'factura'        => $factura,
                                        'historia' 	 => $historia,
					'usuario'  	 => $usuario,
			));
		
			$pdf->AddPage('P', 'A4');
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $examenes, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		}
		if($historia->getPFechaN() != '')
		{
			$pCausaM="";
			$pdx = "";
			if($historia->getPDx()){$pdx = $em->getRepository('HistoriaBundle:Cie')->find($historia->getPDx());}
			if($historia->getPCausaM()){$pCausaM = $em->getRepository('HistoriaBundle:Cie')->find($historia->getPCausaM());}
				
			$historia->setPCausaM($pCausaM);
			$historia->setPDx($pdx);
				
			$parto = $this->renderView('HistoriaBundle:Impresos:Parto.html.twig',array(
					'factura'        => $factura,
                                        'historia' 	 => $historia,
					'usuario'  	 => $usuario,
			));
		
			$pdf->AddPage('P', 'A4');
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $parto, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		}
		$response = new Response($pdf->Output('HcPrint.pdf', 'I'));
		$response->headers->set('Content-Type', 'application/pdf');
	}
	
	public function getHeader($factura,$titulo)
	{
		return $this->renderView('HistoriaBundle:Impresos:header.html.twig',array(
				'factura'  	 => $factura,
				'afiliacion' => $this->afiliacion,
				'paciente' 	 => $this->paciente,
				'historia' 	 => $this->historia,
				'depto'		 => $this->depto,
				'mupio'		 => $this->mupio,
				'titulo'	 => $titulo.$this->historia->getId(),
		));
	}
	
	public function getPritTcpd()
	{
		// se instancia el objeto del tcpdf
		$pdf = $this->get('white_october.tcpdf')->create();
		
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('dejavusans', '', 8, '', true);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(1);
		$pdf->SetFooterMargin(10);
		
		// set image scale factor
		$pdf->setImageScale(5);
		
		$pdf->AddPage();
		
		return $pdf; 
	}
	
	// usuario
	public function setUsuario($usuario)
	{
		$this->usuario = $usuario;
		return $this;
	}
	public function getUsuario()
	{
		return $this->usuario;
	}
	//paciente
	public function setPaciente($paciente)
	{
		$this->paciente = $paciente;
		return $this;
	}
	public function getPaciente()
	{
		return $this->paciente;
	}
	//historia
	public function setHistoria($historia)
	{
		$this->historia = $historia;
		return $this;
	}
	public function getHistoria()
	{
		return $this->historia;
	}		
	//Afiliacion
	public function setAfiliacion($afiliacion)
	{
		$this->afiliacion = $afiliacion;
		return $this;
	}
	public function getAfiliacion()
	{
		return $this->afiliacion;
	}
	//Cliente
	public function setCliente($cliente)
	{
		$this->cliente = $cliente;
		return $this;
	}
	public function getCliente()
	{
		return $this->cliente;
	}	
	// HcCie
	public function setHcCie($hc_cie)
	{
		$this->hc_cie = $hc_cie;
		return $this;
	}
	public function getHcCie()
	{
		return $this->hc_cie;
	}
	//HcExa
	public function setHcExa($hc_exa)
	{
		$this->hc_exa = $hc_exa;
		return $this;
	}
	public function getHcExa()
	{
		return $this->hc_exa;
	}
	//HcLab
	public function setHcLab($hc_lab)
	{
		$this->hc_lab = $hc_lab;
		return $this;
	}
	public function getHcLab()
	{
		return $this->hc_lab;
	}
	//ListNotas
	public function setListNotas($listNotas)
	{
		$this->listNotas = $listNotas;
		return $this;
	}
	public function getListNotas()
	{
		return $this->listNotas;
	}
	
	public function setDepto($depto)
	{
		$this->depto =$depto;
		return $this;
	}
	public function getDepto()
	{
		return $this->depto;
	}
	
	public function setMupio($mupio)
	{
		$this->mupio = $mupio;
		return $this;
	}
	public function getMupio()
	{
		return $this->mupio;
	}
	
	public function getCausaExterna()
	{
		return $causaExt = array(
						'1' => 'Accidente de trabajo',
						'2' => 'Accidente de tránsito',
						'3' => 'Accidente rábico',
						'4' => 'Accidente ofídico',
						'5' => 'Otro tipo de accidente',
						'6' => 'Evento catastrófico',
						'7' => 'Lesión por agresión',
						'8' => 'Lesión auto infligida',
						'9' => 'Sospecha de maltrato físico',
						'10' => 'Sospecha de abuso sexual',
						'11' => 'Sospecha de violencia sexual',
						'12' => 'Sospecha de maltrato emocional',
						'13' => 'Enfermedad general',
						'14' => 'Enfermedad profesional',
						'15' => 'Otra');
	}
	
	
	// validar la informacion para generar el pdf de la historia de ODONTOLOGIA
	public function printOdgAction($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
		
		if(!$factura){
			throw $this->createNotFoundException('Error!! La información solicitada es incorrecta.');
		}
		
		$this->setUsuario($this->get('security.context')->getToken()->getUser());
		$this->setHistoria($factura->getHc());
		$this->setPaciente($factura->getPaciente());
		$this->setCliente($factura->getCliente());
		$this->setAfiliacion($em->getRepository('ParametrizarBundle:Afiliacion')->findOneBy(array('cliente' => $this->getCliente()->getId(), 'paciente' => $this->getPaciente()->getId())));
		$this->setHcCie($em->getRepository('HistoriaBundle:Hc')->findHcDx($this->getHistoria()->getId()));		
		$this->setMupio($em->getRepository('ParametrizarBundle:Mupio')->find($this->getPaciente()->getMupio()));
		$this->setDepto($em->getRepository('ParametrizarBundle:Depto')->find($this->getPaciente()->getDepto()));
		
		$this->generarOdfPdf($factura);
	}
	
	// generar el pdf de la historia de odontologia.
	public function generarOdfPdf($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		$usuario = $this->getUsuario();
		$historia = $this->getHistoria();
		$paciente = $this->getPaciente();
		$cliente = $this->getCliente();
		$afiliacion = $this->getAfiliacion();
		$hc_cie = $this->getHcCie();
		$depto = $this->getDepto();
		$mupio = $this->getMupio();
		$titulo = "Historia Clinica Odontologica No.";
		
		// verificar que los servicios existan para evitar posibles errores ya q se usan los objetos en el impreso
		if($historia->getServiEgre()){
			$serviEgre = $em->getRepository('ParametrizarBundle:Servicio')->find($historia->getServiEgre());
		}else{
			$serviEgre="";
		}
		// si los servicios existen se asignan a la historia para manejarlos como objetos.
		$historia->setServiEgre($serviEgre);
		
		// se ingresan los valores de cada atributo por medio de un array opteniendo el nombre de este con el id de su columna
		$tipoDx = array('1' => 'Impresion diagnostica','2' => 'Confirmado nuevo','3' => 'Repetido');
		$causaExt = $this->getCausaExterna();
		
		$historia->setTipoDx($tipoDx[$historia->getTipoDx()]);		
		$historia->setCausaExt($causaExt[$historia->getCausaExt()]);
		
		// se instancia el tcpdBundle
		$pdf = $this->getPritTcpd();
		
		// se genera el encabezado
		$header = $this->getHeader($factura,$titulo);
		
		$body = $this->renderView('HistoriaBundle:Impresos:OdontologiaPdf.html.twig',array(
				'factura'  	 => $factura,
				'usuario'  	 => $usuario,
				'historia' 	 => $historia,
				'hc_cie' 	 => $hc_cie,				
		));
		
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $header, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $body, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
		
		$response = new Response($pdf->Output('HcPrint.pdf', 'I'));
		$response->headers->set('Content-Type', 'application/pdf');
	}
}
