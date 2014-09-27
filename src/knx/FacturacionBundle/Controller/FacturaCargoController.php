<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FacturacionBundle\Entity\FacturaCargo;
use knx\ParametrizarBundle\Entity\Cliente;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

use knx\FacturacionBundle\Form\FacturaCargoType;
use knx\FacturacionBundle\Form\ConsolidadoType;


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

			$this->get('session')->setFlash('error', 'Opcion No Validad, Vuelva A seleccionar Una Opcion.');
			return $this->redirect($this->generateUrl('reporte_cargo_new'));
			
		}else{
			$this->get('session')->setFlash('error', 'Opcion No Validad, Vuelva A seleccionar Una Opcion.');
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
        
        
        
        public function generarConsolidadoAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$clientes = $em->getRepository("ParametrizarBundle:Cliente")->findAll();
    	 
    	$plantilla = 'FacturacionBundle:Consolidado:consolidado_final.html.twig';
    	
    
    	return $this->render($plantilla, array(
    			'clientes' => $clientes,
    	));
    }
    
    
    
    public function resultConsolidadoAction()
    {
    	 
    	$request = $this->get('request');
    	 
    	$cliente = $request->request->get('cliente');
    	$f_inicio = $request->request->get('f_inicio');
    	$f_fin = $request->request->get('f_fin');
    	
    	$url = 'consolidados_vista';
    	 
    	if(trim($f_inicio)){
    		$desde = explode('/',$f_inicio);
    
    		if(!checkdate($desde[1],$desde[0],$desde[2])){
    			$this->get('session')->setFlash('info', 'La fecha de inicio ingresada es incorrecta.');
    			return $this->redirect($this->generateUrl($url));
    		}
    	}else{
    		$this->get('session')->setFlash('info', 'La fecha de inicio no puede estar en blanco.');
    		return $this->redirect($this->generateUrl($url));
    	}
    	 
    	if(trim($f_fin)){
    		$hasta = explode('/',$f_fin);
    
    		if(!checkdate($hasta[1],$hasta[0],$hasta[2])){
    			$this->get('session')->setFlash('info', 'La fecha de finalización ingresada es incorrecta.');
    			return $this->redirect($this->generateUrl($url));
    		}
    	}else{
    		$this->get('session')->setFlash('info', 'La fecha de finalización no puede estar en blanco.');
    		return $this->redirect($this->generateUrl($url));
    	}
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	
    	if(is_numeric(trim($cliente))){
    		$obj_cliente = $em->getRepository("ParametrizarBundle:Cliente")->find($cliente);
    	}else{
    		$obj_cliente['nombre'] = 'Todos los clientes.';
    		$obj_cliente['id'] = '';
    	}    	
    	 
    	if(!$obj_cliente){
    		$this->get('session')->setFlash('info', 'El cliente seleccionado no existe.');
    		return $this->redirect($this->generateUrl($url));
    	}
    	
    	
    	
    	 
    	$dql= " SELECT
			    	f.id,
			    	p.id as paciente,
			    	p.tipoId,
			    	p.identificacion,
			    	f.fecha,
			    	f.autorizacion,
			    	p.priNombre,
			    	p.segNombre,
			    	p.priApellido,
			    	p.segApellido,
			    	fc.pagoPte,
			    	fc.recoIps,
                                SUM(fc.valorTotal) AS total,
                                SUM(fc.pagoPte) AS copago,
                                SUM(fc.recoIps) AS asumido


    			FROM
    				FacturacionBundle:FacturaCargo fc
    			JOIN
    				fc.factura f
    			JOIN
    				f.paciente p
    			JOIN
    				f.cliente c
    			WHERE
                                c.id = :cliente
			    	AND f.fecha > :inicio
			    	AND f.fecha <= :fin
                                AND f.estado != 'X'
                        GROUP BY fc.factura
		    	ORDER BY
		    		fc.factura ASC";
    
    	$query = $em->createQuery($dql);
    	 
    	$query->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    	$query->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');
    	$query->setParameter('cliente', $cliente);
    	 
    	$consolidado = $query->getResult();
        
                                
           //die(var_dump($consolidado));                     
        if(!$consolidado)
    	{
    		$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    		return $this->redirect($this->generateUrl('consolidados_vista'));
    	}


    	$pdf = $this->get('white_october.tcpdf')->create();
    	
        
        
         $html = $this->renderView('FacturacionBundle:Consolidado:listado.html.twig',array(
                            'cliente' =>$obj_cliente,
                            'f_inicio' =>$f_inicio,
                            'f_fin'    => $f_fin,
                            'consolidado' => $consolidado
                ));
        
         return $pdf->quick_pdf($html, 'consolidado'.$obj_cliente->getNombre().'.pdf', 'D');  

    	
    	
    }
    
    
    
    
}
