<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FacturacionBundle\Entity\FacturaCargo;
use knx\FacturacionBundle\Entity\Usuario;
use knx\FacturacionBundle\Entity\FacturaFinal;


use knx\ParametrizarBundle\Entity\Cliente;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

use knx\FacturacionBundle\Form\FacturaCargoType;
use knx\FacturacionBundle\Form\FacturacionType;
use knx\FacturacionBundle\Form\FacturasFinalType;
use knx\FacturacionBundle\Form\MotivoFinalType;


class FacturaCargoController extends Controller
{
	// como titulo el nombre del reporte a presentar con su respectiva fecha en que se genera asi mismo con la fecha
	// desde y hasta que se genera el cargo.

	//CodigoCargo, NombreCargo  cantidadesCargoPrestado, ValorUnitario y el CostoTotale

	public function informeCargoAction()
	{
                $breadcrumbs = $this->get("white_october_breadcrumbs");
                $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
                $breadcrumbs->addItem("reportes", $this->get("router")->generate("reporte_cargo_question"));
            
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
                                                'dateStart' => $dateStart,
						'dateEnd' => $dateEnd,
						'facturaCargo' => $facturaCargo
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
                                                'dateStart' => $dateStart,
						'dateEnd' => $dateEnd,
						'facturaCargo' => $facturaCargo
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
                                                'dateStart' => $dateStart,
						'dateEnd' => $dateEnd,
						'facturaCargo' => $facturaCargo
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
            
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("consolidado", $this->get("router")->generate("consolidados_vista"));
        $breadcrumbs->addItem("consulta");

    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$clientes = $em->getRepository("ParametrizarBundle:Cliente")->findAll();
    	 
    	  	
    
    	return $this->render('FacturacionBundle:Consolidado:consolidado_final.html.twig', array(
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
    	 
    	
    	if(is_object($obj_cliente)){
    		$con_cliente = "AND f.cliente =".$cliente;
    	}else{
    		$con_cliente = "";
    	}
    		 
    	$dql= " SELECT
			    	f.id,
                                f.estado,
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
                                SUM(fc.vrFacturado) AS facturado,
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


    	
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("consolidado Consulta", $this->get("router")->generate("consolidados_vista"));
        $breadcrumbs->addItem("Resultado");

            return $this->render('FacturacionBundle:Consolidado:consolidado_list.html.twig',array(
                            'cliente' =>$obj_cliente,
                            'f_inicio' =>$f_inicio,
                            'f_fin'    => $f_fin,
                            'consolidado' => $consolidado
                ));
        

    	
    	
    }
    
    
    
    
     public function searchConsolidadoAction()
                
    {
            
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Consolidado", $this->get("router")->generate("consolidados_vista_imprimir"));
        $breadcrumbs->addItem("Imprimir");

        
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$clientes = $em->getRepository("ParametrizarBundle:Cliente")->findAll();
    	 
    	  	
    
    	return $this->render('FacturacionBundle:Consolidado:consolidado_print_search.html.twig', array(
    			'clientes' => $clientes,
    	));
    }
    
    
    public function printConsolidadoAction()
    {
    	 
    	$request = $this->get('request');
    	 
    	$cliente = $request->request->get('cliente');
    	$f_inicio = $request->request->get('f_inicio');
    	$f_fin = $request->request->get('f_fin');
    	
    	$url = 'consolidados_vista_imprimir';
    	 
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
                                SUM(fc.vrFacturado) AS facturado,
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

    		return $this->redirect($this->generateUrl('consolidados_vista_imprimir'));
    	}


    	$pdf = $this->get('white_october.tcpdf')->create();
    	
        
        
         $html = $this->renderView('FacturacionBundle:Consolidado:consolidado_print.html.twig',array(
                            'cliente' =>$obj_cliente,
                            'f_inicio' =>$f_inicio,
                            'f_fin'    => $f_fin,
                            'consolidado' => $consolidado
                ));
        
         return $pdf->quick_pdf($html, 'consolidado'.$obj_cliente->getNombre().'.pdf', 'D');  

    	
    	
    }
    
    public function cierreCajaAction()
            
    {
        
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cierre", $this->get("router")->generate("cierre_vista"));
        $breadcrumbs->addItem("Consultar");

        
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$facturadores = $em->getRepository("UsuarioBundle:Usuario")->findBy(array('cargo' => 'FACTURADOR'), array('nombre' => 'ASC'));
    	//die(var_dump($facturadores));
    	    
    	return $this->render('FacturacionBundle:CierreCaja:cierreFinal.html.twig', array(
    			'facturadores' => $facturadores
    	));
    }
    
    
    
    public function resultCierreAction()
    {
    	 
    	$request = $this->getRequest();

    	$facturador = $request->request->get('facturador');
    	$f_inicio = $request->request->get('f_inicio');
    	$f_fin = $request->request->get('f_fin');
    	//die(var_dump($facturador));                     

    	$url = 'cierre_vista';
    	 
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
    	 
    	
    	if(is_numeric(trim($facturador))){
    		$obj_facturador = $em->getRepository("UsuarioBundle:Usuario")->find($facturador);
    	}else{
    		$obj_facturador['nombre'] = 'Todos los clientes.';
    		$obj_facturador['id'] = '';
    	}    	
    	 
    	if(!$obj_facturador){
    		$this->get('session')->setFlash('info', 'El facturador seleccionado no existe.');
    		return $this->redirect($this->generateUrl($url));
    	}
    	
    	
    	    	//die(var_dump($obj_facturador));                     

    	 
    	$dql= " SELECT
			    	f.id AS factura,
                                f.estado,
			    	p.id as paciente,
			    	p.tipoId,
			    	p.identificacion,
			    	f.fecha,
			    	p.priNombre,
			    	p.segNombre,
			    	p.priApellido,
			    	p.segApellido,
			    	fc.pagoPte,
			    	fc.recoIps,
                                u.id,
                                c.nombre,
                                SUM(fc.vrFacturado) AS facturado,
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
    				f.usuario u
                        JOIN
    				f.cliente c        
    			WHERE
                                u.id = :facturador
			    	AND f.fecha > :inicio
			    	AND f.fecha <= :fin
                                AND f.estado != 'X'
                        GROUP BY fc.factura
		    	ORDER BY
		    		fc.factura ASC";
    
    	$query = $em->createQuery($dql);
    	 
    	$query->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    	$query->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');
    	$query->setParameter('facturador', $facturador);
    	 
    	$cierre = $query->getResult();
        
                                
        //die(var_dump($cierre));                     
        if(!$cierre)
    	{
    		$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    		return $this->redirect($this->generateUrl('cierre_vista'));
    	}


    	//$pdf = $this->get('white_october.tcpdf')->create();
    	
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Consultar Cierre", $this->get("router")->generate("cierre_vista"));
        $breadcrumbs->addItem("Resultado");

            return $this->render('FacturacionBundle:CierreCaja:listado.html.twig',array(
                            'facturador' =>$facturador,
                            'facturas'  =>$obj_facturador,
                            'f_inicio' =>$f_inicio,
                            'f_fin'    => $f_fin,
                            'cierre' => $cierre
                ));
        
         //return $pdf->quick_pdf($html, 'RECUADO_'.$obj_facturador->getNombre().'.pdf', 'D');  

    	
    	
    } 
    
    
    public function searchCierreAction()
            
    {
        
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Cierre", $this->get("router")->generate("cierre_vista_imprimir"));
        $breadcrumbs->addItem("Imprimir");

    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$facturadores = $em->getRepository("UsuarioBundle:Usuario")->findBy(array('cargo' => 'FACTURADOR'), array('nombre' => 'ASC'));
    	//die(var_dump($facturadores));
    	    
    	return $this->render('FacturacionBundle:CierreCaja:cierre_final_search.html.twig', array(
    			'facturadores' => $facturadores
    	));
    }
    
   
     public function printCierreAction()
    {
    	 
    	$request = $this->getRequest();

    	$facturador = $request->request->get('facturador');
    	$f_inicio = $request->request->get('f_inicio');
    	$f_fin = $request->request->get('f_fin');
    	//die(var_dump($facturador));                     

    	$url = 'cierre_vista_imprimir';
    	 
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
    	 
    	
    	if(is_numeric(trim($facturador))){
    		$obj_facturador = $em->getRepository("UsuarioBundle:Usuario")->find($facturador);
    	}else{
    		$obj_facturador['nombre'] = 'Todos los clientes.';
    		$obj_facturador['id'] = '';
    	}    	
    	 
    	if(!$obj_facturador){
    		$this->get('session')->setFlash('info', 'El facturador seleccionado no existe.');
    		return $this->redirect($this->generateUrl($url));
    	}
    	
    	
    	    	//die(var_dump($obj_facturador));                     

    	 
    	$dql= " SELECT
			    	f.id AS factura,
			    	p.id as paciente,
			    	p.tipoId,
			    	p.identificacion,
			    	f.fecha,
			    	p.priNombre,
			    	p.segNombre,
			    	p.priApellido,
			    	p.segApellido,
			    	fc.pagoPte,
			    	fc.recoIps,
                                u.id,
                                c.nombre,
                                SUM(fc.vrFacturado) AS facturado,
                                SUM(fc.pagoPte) AS copago,
                                SUM(fc.recoIps) AS asumido,
                                SUM(fc.valorTotal) AS total


    			FROM
    				FacturacionBundle:FacturaCargo fc
    			JOIN
    				fc.factura f
    			JOIN
    				f.paciente p
    			JOIN
    				f.usuario u
                        JOIN
    				f.cliente c        
    			WHERE
                                u.id = :facturador
			    	AND f.fecha > :inicio
			    	AND f.fecha <= :fin
                                AND f.estado != 'X'
                        GROUP BY fc.factura
		    	ORDER BY
		    		fc.factura ASC";
    
    	$query = $em->createQuery($dql);
    	 
    	$query->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    	$query->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');
    	$query->setParameter('facturador', $facturador);
    	 
    	$cierre = $query->getResult();
        
                                
        //die(var_dump($cierre));                     
        if(!$cierre)
    	{
    		$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    		return $this->redirect($this->generateUrl('cierre_vista_imprimir'));
    	}


    	$pdf = $this->get('white_october.tcpdf')->create();
    	
        
        
         $html = $this->renderView('FacturacionBundle:CierreCaja:listado_print.html.twig',array(
                            'facturador' =>$facturador,
                            'facturas'  =>$obj_facturador,
                            'f_inicio' =>$f_inicio,
                            'f_fin'    => $f_fin,
                            'cierre' => $cierre
                ));
        
         return $pdf->quick_pdf($html, 'RECUADO_'.$obj_facturador->getNombre().'.pdf', 'D');  

    	
    	
    }
    
    
    
    public function facturaFinalAction()
                
    {
            
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Factura Final", $this->get("router")->generate("consolidados_vista_imprimir"));
        $breadcrumbs->addItem("Imprimir");

        
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$clientes = $em->getRepository("ParametrizarBundle:Cliente")->findAll();
    	 
    	  	
    
    	return $this->render('FacturacionBundle:FacturaFinal:factura_final.html.twig', array(
    			'clientes' => $clientes,
    	));
    }
    
    public function resultFactfinalAction(){


        $request = $this->get('request');
    	 
    	$cliente = $request->request->get('cliente');
    	$f_inicio = $request->request->get('f_inicio');
    	$f_fin = $request->request->get('f_fin');
    	
    	$url = 'factura_final_vista';
    	$em = $this->getDoctrine()->getEntityManager();

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
                                SUM(fc.vrFacturado) AS facturado,
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
                                AND f.estado != 'X'";
                       
    
    	$query = $em->createQuery($dql);
    	 
    	$query->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    	$query->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');
    	$query->setParameter('cliente', $cliente);
    	 
    	$final = $query->getSingleResult();
        //die(var_dump($final));                        
        $entity = new FacturaFinal();
    	
    	$entity->setInicio($f_inicio);
    	$entity->setFin($f_fin);
    	$entity->setConcepto('');
        $entity->setValor($final['facturado']);
    	$entity->setCopago($final['copago']);
        $entity->setAsumido($final['asumido']);
    	$entity->setIva(0); 
        $entity->setCobrar(0);
        
        $form   = $this->createForm(new FacturacionType(), $entity);

        if(!$final)
    	{
    		$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    		return $this->redirect($this->generateUrl('factura_final_vista'));
    	}


    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Factura Final", $this->get("router")->generate("factura_final_vista"));
        $breadcrumbs->addItem("Resultado");

            return $this->render('FacturacionBundle:FacturaFinal:print_factura.html.twig',array(
                            'cliente' =>$obj_cliente,
                            'f_inicio' =>$f_inicio,
                            'f_fin'    => $f_fin,
                            'final' => $final,
                            'form'   => $form->createView()

                ));  

    	
    	
    }
    
    public function saveFactfinalAction($cliente){
        
        $entity  = new FacturaFinal();
    
    	$request = $this->getRequest();
    	$form    = $this->createForm(new FacturacionType(), $entity);
    	$form->bindRequest($request);
    
    	if ($form->isValid()) {
    		
    		$datos = $form->getData();
                $inicio = $datos->getInicio();
                $fin = $datos->getFin();

    		$em = $this->getDoctrine()->getEntityManager();
    		//die(var_dump($datos));
    		$cliente = $em->getRepository('ParametrizarBundle:Cliente')->find($cliente);
    		$desde = explode('/',$inicio);
                $hasta = explode('/',$fin);
                $inicio_ord = $desde[2]."/".$desde[1]."/".$desde[0];
                $fin_ord = $hasta[2]."/".$hasta[1]."/".$hasta[0];
                

    		$iniciob = new \DateTime($inicio_ord);
    		$finb = new \DateTime($fin_ord);
                //die(var_dump($finb));                

    		$entity->setFecha(new \DateTime('now'));
                $entity->setFR(new \DateTime('now'));
    		$entity->setInicio($iniciob);
    		$entity->setFin($finb);
    		$entity->setEstado('G');
    		$entity->setTipo('F');
    		$entity->setCopago($datos->getCopago());
                $entity->setAsumido($datos->getAsumido());
                $entity->setCobrar($datos->getValor()-$datos->getCopago()-$datos->getAsumido());

    		$entity->setCliente($cliente);
    		   
    		$em->persist($entity);
    		$em->flush();
    
    		$this->get('session')->setFlash('info', 'La información de la factura ha sido registrada éxitosamente.');
    
    		return $this->redirect($this->generateUrl('factura_final_show',array("id"=>$entity->getId())));
    
    	}
    
    	 return $this->render('FacturacionBundle:FacturaFinal:print_factura.html.twig',array(
                            'cliente' =>$obj_cliente,
                            'f_inicio' =>$iniciob,
                            'f_fin'    => $finb,
                            'form'   => $form->createView()

                ));  
    
    }
    
    
    public function showFactfinalAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$factura = $em->getRepository('FacturacionBundle:FacturaFinal')->find($id);
    
    
    	if (!$factura) {
    		throw $this->createNotFoundException('La factura solicitada no esta disponible.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Factura Final", $this->get("router")->generate("factura_final_vista"));
        $breadcrumbs->addItem("Imprimir");
    
    	return $this->render('FacturacionBundle:FacturaFinal:factura_final_show.html.twig', array(
    			'entity'  => $factura    
    	));
    }
    
    
    public function  imprimirFactfinalAction($id)
    {
        
        
        set_time_limit(0);
    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$entity = $em->getRepository('FacturacionBundle:FacturaFinal')->find($id);
    	 
    	if (!$entity) {
    		throw $this->createNotFoundException('La factura a imprimir no esta disponible.');
    	}

        $pdf = $this->get('white_october.tcpdf')->create();

    	$html = $this->renderView('FacturacionBundle:FacturaFinal:factura_venta.pdf.twig',
    			array('entity' 	=> $entity,
    			));
    	 
    	 
    	
        return $pdf->quick_pdf($html, 'factura_venta_'.$entity->getId().'.pdf', 'D');  

        
    }        
            
            
            public function searchFinalAction()
                
        {
            
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	    $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_search"));
    	
            $form   = $this->createForm(new FacturasFinalType());

            $em = $this->getDoctrine()->getEntityManager();
            $facturas = $em->getRepository('FacturacionBundle:FacturaFinal')->findAll();

            if (!$facturas) {
    		$this->get('session')->setFlash('info', 'No existe factura');
            }

            return $this->render('FacturacionBundle:FacturaFinal:search.html.twig', array(
    			'form'   => $form->createView()
            ));
        }
        
        
        
        public function listAction()
	{
            
                
		$request = $this->getRequest();
		$form    = $this->createForm(new FacturasFinalType());
		$form->bindRequest($request);
		
		if ($form->isValid()) 
		{
			// se optienen todos los datos del formulario para ser procesado de forma individual 
			
			
		$idfactura = $form->get('factura')->getData();

	 	if(((trim($idfactura) && is_numeric($idfactura)))){

	 		$em = $this->getDoctrine()->getEntityManager();
	 		$factura = $em->getRepository('FacturacionBundle:FacturaFinal')->findOneBy(array('id' => $idfactura));
                        //die(var_dump($factura));

                        $breadcrumbs = $this->get("white_october_breadcrumbs");
                        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
                        $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_search"));
                        $breadcrumbs->addItem($idfactura);

                        
	 		if(!$factura){
	 			$this->get('session')->setFlash('info', 'El número de factura no se encuentra.');
	 				
	 			return $this->redirect($this->generateUrl('facturas_final_search'));
	 		}
                        
                        $est_fact = $factura->getEstado();

                        if ($est_fact=='X'){
                            
                            $this->get('session')->setFlash('info', 'Factura ya Anulada.');
	 				
	 		     return $this->redirect($this->generateUrl('facturas_final_search'));
                            
                        }
	 			
	 		$dql = $em->createQuery("SELECT f
						  FROM
						  FacturacionBundle:FacturaFinal f 
											
						  WHERE 
	         				  f.id = :id
											");	 			
                        	 			
	 		$dql->setParameter('id', $factura->getId());
	 		$dql->getSql();
	 		$facturas = $dql->getResult();
	 		//die(var_dump($facturas));
	 		if(!$facturas)
	 		{
	 			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');
	 			
				return $this->redirect($this->generateUrl('factura_final_search'));
	 		}
	 			
	 		return $this->render('FacturacionBundle:FacturaFinal:list.html.twig', array(
	 				'facturas1' => $facturas,
	 				
	 				'form'   => $form->createView()
	 		));	 			
	 	}else{
	 		$this->get('session')->setFlash('info', 'Los parametros de busqueda ingresados son incorrectos.');
	 			
	 		return $this->redirect($this->generateUrl('facturas_final_search'));
	 	}	 			
	}		
			                       

                
    }
    
    
    public function motivoFactfinalAction($factura1)
                
        {
            $em = $this->getDoctrine()->getEntityManager();

            $facturas = $em->getRepository('FacturacionBundle:FacturaFinal')->find($factura1);
    	
            $fact_num = $facturas->getId();
           
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
            $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_search"));
            $breadcrumbs->addItem($fact_num,$this->get("router")->generate('facturas_search'));
            $breadcrumbs->addItem("Anular");

            $form   = $this->createForm(new MotivoFinalType(), $facturas);
            //$fact = $form["factura"]->setData($fact_num);
            //$idpa = $form["idp"]->setData($id_paciente);
             //die(var_dump($fact));
            //$almacenf = $form["almacen"]->setData($nombre_almacen);

            return $this->render('FacturacionBundle:FacturaFinal:motivo.html.twig', array(
                            'facturas' => $facturas,
                            'form'   => $form->createView()
            ));
    }
    
    public function anularFactfinalAction($factura1)

                {

                    $em = $this->getDoctrine()->getEntityManager();
                    $facturas = $em->getRepository('FacturacionBundle:FacturaFinal')->find($factura1);
                    $request = $this->getRequest();
                    $est_fact = $facturas->getEstado();
                    $form = $this->createForm(new MotivoFinalType(), $facturas);
                    $request = $this->getRequest();
                    $form->bind($request);
                    $motivo = $form->get('motivo')->getData();
                    $nfactura = $form->get('nfactura')->getData();
                
                 if($facturas){
                    
                    
                    $facturas->setEstado('X');
                    $facturas->setMotivo($motivo);
                    $facturas->setNfactura($nfactura);    

                    $em->persist($facturas);
                    
                    $em->flush();

                    $this->get('session')->setFlash('ok', 'La Factura ha sido Anulada.');
    			return $this->redirect($this->generateUrl('facturas_final_search'));
                    
                }
                else{	
               $this->get('session')->setFlash('info', 'Los parametros de busqueda ingresados son incorrectos.');
	 			
	 		return $this->redirect($this->generateUrl('facturas_final_search'));
                }
            } 
            
            
             public function rimprimirFactfinalAction($factura1)
	{
		$em = $this->getDoctrine()->getEntityManager();
    	
                $factura = $em->getRepository('FacturacionBundle:FacturaFinal')->find($factura1);
    	
    	
                if (!$factura) {
                        throw $this->createNotFoundException('La factura solicitada no existe');
                }
    	

                // se consulta por la informacion del profesional para ser visulizada en la factura.
                //$profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
                //$factura->setProfesional($profesional->getNombre().' '.$profesional->getApellido());

                $pdf = $this->get('white_october.tcpdf')->create();

                $html = $this->renderView('FacturacionBundle:FacturaFinal:factura_venta.pdf.twig',array(
                                                                        'entity' => $factura,
                ));

                return $pdf->quick_pdf($html, 'factura_venta_'.$factura->getId().'.pdf', 'D');  
}
}
