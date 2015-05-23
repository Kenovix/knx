<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use knx\FacturacionBundle\Entity\Factura;
use knx\HistoriaBundle\Entity\Hc;

use knx\FacturacionBundle\Form\FacturaType;

use knx\ParametrizarBundle\Form\AfiliacionType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use knx\FacturacionBundle\Entity\FacturaCargo;
use knx\FacturacionBundle\Entity\FacturaImv;
use knx\FarmaciaBundle\Entity\ImvFarmacia;

class FacturaController extends Controller
{
    public function newConsultaAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_consulta_new"));
    	
    	$fecha = new \DateTime('now');
    	
    	$factura = new Factura();
    	
    	$factura->setFecha($fecha);
    	
    	$form = $this->createForm(new FacturaType(), $factura);
    	
    	$form_afiliacion = $this->createForm(new AfiliacionType()); 

    	return $this->render('FacturacionBundle:Factura:new_consulta.html.twig', array(
    			'form'   => $form->createView(),
    			'form_afiliacion' => $form_afiliacion->createView()
    	));
    }
    
    
    public function saveConsultaAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_consulta_new"));
    	
    	$factura = new Factura();
    	
    	$form = $this->createForm(new FacturaType(), $factura);
    	$request = $this->getRequest();
    	$entity = $request->get($form->getName());

		$em = $this->getDoctrine()->getEntityManager();
		
		$paciente = $em->getRepository("ParametrizarBundle:Paciente")->findOneBy(array("identificacion" => $entity['paciente']));
		$cliente = $em->getRepository("ParametrizarBundle:Cliente")->find($entity['cliente']);
		$servicio = $em->getRepository("ParametrizarBundle:Servicio")->find($entity['servicio']);

		$usuario = $this->get('security.context')->getToken()->getUser();
		
		if (array_key_exists('pyp', $entity)){
			$pyp = $entity['pyp'];
		}else{
			$pyp = null;
		}
		
		$str_fecha = $entity['fecha']['date']['year'].'-'.$entity['fecha']['date']['month'].'-'.$entity['fecha']['date']['day'].' '.$entity['fecha']['time']['hour'].':'.$entity['fecha']['time']['minute'];
		
		$fecha = new \DateTime($str_fecha);
		
		$factura->setPaciente($paciente);
		$factura->setCliente($cliente);
		$factura->setServicio($servicio);
		$factura->setUsuario($usuario);
		$factura->setFecha($fecha);
		$factura->setAutorizacion($entity['autorizacion']);
		$factura->setObservacion($entity['observacion']);
		$factura->setProfesional($entity['profesional']);
		$factura->setPyp($pyp);
		$factura->setEstado('A');
		
		if($factura->getServicio() == 'CONSULTA EXTERNA' || $factura->getServicio() == 'ODONTOLOGIA'){
			$factura->setTipo('C');
		}else{
			$factura->setTipo('U');
		}
    	  
    	$em->persist($factura);
    	$em->flush();
    		 
    	$this->get('session')->setFlash('ok', 'La factura ha sido registrada éxitosamente.');
    			 
    	return $this->redirect($this->generateUrl('facturacion_consulta_show', array("factura" => $factura->getId())));
    		
    }
    
    public function showConsultaAction($factura)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    	 
    	if (!$factura) {
    		throw $this->createNotFoundException('La factura solicitada no esta disponible.');
    	}
    	
    	$factura_cargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findBy(array('factura' => $factura->getId()));
    	
    	if($factura->getPyp()){
    		$pyp = $em->getRepository('ParametrizarBundle:Pyp')->find($factura->getPyp());
    		
    		$dql = $em->createQuery( "SELECT
										c.id,
    									c.nombre
									 FROM
										ParametrizarBundle:CargoPyp cp
									 JOIN
										cp.cargo c
									 WHERE
										c.tipoCargo = 'CE' AND
    									cp.pyp = :categoria
									 ORDER BY
										c.nombre ASC");
    		
    		$dql->setParameter('categoria', $pyp->getId());
    		
    		$consultas = $dql->getResult();
    		
    	}else{
    		$pyp = "";
    		
    		if ($factura->getTipo() == 'C') {
    			$tipo_cargo = 'CE';
    		}else{
    			$tipo_cargo = 'CU';
    		}

    		$dql = $em->createQuery( "SELECT
									c.id,
    									c.nombre
									 FROM
										ParametrizarBundle:ContratoCargo cc
									 JOIN
										cc.cargo c
    								 JOIN
    									cc.contrato ct
    								 JOIN
    									ct.cliente cli
									 WHERE
									c.tipoCargo = :tipoCargo AND
    									cli.id = :cliente 
									 ORDER BY
										c.nombre ASC");

    		$dql->setParameter('tipoCargo', $tipo_cargo);
    		$dql->setParameter('cliente', $factura->getCliente()->getId());
    		
    		$consultas = $dql->getResult();
    	}
    	
    	if($factura->getProfesional()){
    		$profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
    	}else{
    		$profesional = "";
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_consulta_new"));
    	 
    	return $this->render('FacturacionBundle:Factura:show_consulta.html.twig', array(
    			'factura'  => $factura,
    			'cargos' => $factura_cargo,
    			'pyp' => $pyp,
    			'consultas' => $consultas,
    			'profesional' => $profesional
    	));
    }
    
    public function editConsultaAction($factura)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    
    	if (!$factura) {
    		throw $this->createNotFoundException('La factura solicitada no existe');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_consulta_new"));

    	$form = $this->createForm(new FacturaType(), $factura);
    
    	return $this->render('FacturacionBundle:Factura:edit_consulta.html.twig', array(
    			'factura' => $factura,
    			'form' => $form->createView()
    	));
    }
    
    
    public function newProcedimientoAction($tipo)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura");
    
        $fecha = new \DateTime('now');
    	
    	$factura = new Factura();
    	
    	$factura->setFecha($fecha);
        
    	$form = $this->createForm(new FacturaType(), $factura);
    
    	$form_afiliacion = $this->createForm(new AfiliacionType());
    
    	return $this->render('FacturacionBundle:Factura:new_procedimiento.html.twig', array(
    			'tipo' => $tipo,
    			'form'   => $form->createView(),
    			'form_afiliacion' => $form_afiliacion->createView()
    	));
    }
    
    
    public function saveProcedimientoAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura");
    
    	$factura = new Factura();
    
    	$form = $this->createForm(new FacturaType(), $factura);
    	$request = $this->getRequest();
    	$entity = $request->get($form->getName());
    
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$paciente = $em->getRepository("ParametrizarBundle:Paciente")->findOneBy(array("identificacion" => $entity['paciente']));
    	$cliente = $em->getRepository("ParametrizarBundle:Cliente")->find($entity['cliente']);
    	$servicio = $em->getRepository("ParametrizarBundle:Servicio")->find($entity['servicio']);
    
    	$usuario = $this->get('security.context')->getToken()->getUser();
    
    	if (array_key_exists('pyp', $entity)){
    		$pyp = $entity['pyp'];
    	}else{
    		$pyp = null;
    	}
    
        $str_fecha = $entity['fecha']['date']['year'].'-'.$entity['fecha']['date']['month'].'-'.$entity['fecha']['date']['day'].' '.$entity['fecha']['time']['hour'].':'.$entity['fecha']['time']['minute'];
		
	$fecha = new \DateTime($str_fecha);
    	$factura->setPaciente($paciente);
    	$factura->setCliente($cliente);
    	$factura->setServicio($servicio);
    	$factura->setUsuario($usuario);
    	$factura->setFecha($fecha);
    	$factura->setAutorizacion($entity['autorizacion']);
    	$factura->setObservacion($entity['observacion']);
    	$factura->setProfesional($entity['profesional']);
    	$factura->setPyp($pyp);
    	$factura->setEstado('A');
    	$factura->setTipo('P');
    
    	$em->persist($factura);
    	$em->flush();
    
    	$this->get('session')->setFlash('ok', 'La factura ha sido registrada éxitosamente.');
    
    	return $this->redirect($this->generateUrl('facturacion_procedimiento_show', array("factura" => $factura->getId())));
    }
    
    
    public function showProcedimientoAction($factura)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    
    	if (!$factura) {
    		throw $this->createNotFoundException('La factura solicitada no esta disponible.');
    	}
    	 
    	$factura_cargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findBy(array('factura' => $factura->getId()));
    	 
    	if($factura->getPyp()){
    		$pyp = $em->getRepository('ParametrizarBundle:Pyp')->find($factura->getPyp());
    
    		$dql = $em->createQuery( "SELECT
										c.id,
    									c.nombre
									 FROM
										ParametrizarBundle:CargoPyp cp
									 JOIN
										cp.cargo c
									 WHERE
										c.tipoCargo = 'OS' OR
    									c.tipoCargo = 'LB' OR
    									c.tipoCargo = 'P' AND
    									cp.pyp = :categoria
									 ORDER BY
										c.nombre ASC");
    
    		$dql->setParameter('categoria', $pyp->getId());
    
    		$consultas = $dql->getResult();
    
    	}else{
    		$pyp = "";
    		
    		if($factura->getServicio()->getNombre() == 'LABORATORIO'){
    			$tipo_cargo = 'LB';
    		}elseif ($factura->getServicio()->getNombre() == 'ODONTOLOGIA'){
    			$tipo_cargo = 'PO';
    		}else{
    			$tipo_cargo = 'P';
    		}	
    
    		$dql = $em->createQuery( "SELECT
    									distinct(c.id),
    									c.nombre
									 FROM
										ParametrizarBundle:ContratoCargo cc
									 JOIN
										cc.cargo c
    								 JOIN
    									cc.contrato ct
    								 JOIN
    									ct.cliente cli
									 WHERE
										c.tipoCargo = :tipoCargo OR
    									c.tipoCargo = 'OS' OR
    									c.tipoCargo = 'LB' AND
    									cli.id = :cliente
									 ORDER BY
										c.nombre ASC");
    
    		$dql->setParameter('tipoCargo', $tipo_cargo);
    		$dql->setParameter('cliente', $factura->getCliente()->getId());
    
    		$consultas = $dql->getResult();
    	}
    	 
    	if($factura->getProfesional()){
    		$profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
    	}else{
    		$profesional = "";
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura");
    
    	return $this->render('FacturacionBundle:Factura:show_procedimiento.html.twig', array(
    			'factura'  => $factura,
    			'cargos' => $factura_cargo,
    			'pyp' => $pyp,
    			'consultas' => $consultas,
    			'profesional' => $profesional
    	));
    }
    
    
    public function newInsumoAction($tipo)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura");
    
        $fecha = new \DateTime('now');
    	
    	$factura = new Factura();
    	
    	$factura->setFecha($fecha);
        
    	$form = $this->createForm(new FacturaType(), $factura);
    	
    	$form_afiliacion = $this->createForm(new AfiliacionType());
    	
    	return $this->render('FacturacionBundle:Factura:new_insumo.html.twig', array(
    			'tipo' => $tipo,
    			'form'   => $form->createView(),
    			'form_afiliacion' => $form_afiliacion->createView()
    	));
    }
    
    
    public function saveInsumoAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura");
    
    	$factura = new Factura();
    
    	$form = $this->createForm(new FacturaType(), $factura);
    	$request = $this->getRequest();
    	$entity = $request->get($form->getName());
    
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$paciente = $em->getRepository("ParametrizarBundle:Paciente")->findOneBy(array("identificacion" => $entity['paciente']));
    	$cliente = $em->getRepository("ParametrizarBundle:Cliente")->find($entity['cliente']);
    	//$servicio = $em->getRepository("Bundle:Servicio")->find($entity['farmacia']);
    
    	$usuario = $this->get('security.context')->getToken()->getUser();
    
    	if (array_key_exists('pyp', $entity)){
    		$pyp = $entity['pyp'];
    	}else{
    		$pyp = null;
    	}
        
        $str_fecha = $entity['fecha']['date']['year'].'-'.$entity['fecha']['date']['month'].'-'.$entity['fecha']['date']['day'].' '.$entity['fecha']['time']['hour'].':'.$entity['fecha']['time']['minute'];
		
	$fecha = new \DateTime($str_fecha);
    
    	$factura->setPaciente($paciente);
    	$factura->setCliente($cliente);
    	$factura->setFarmacia($entity['farmacia']);
    	$factura->setUsuario($usuario);
    	$factura->setFecha($fecha);
    	$factura->setAutorizacion($entity['autorizacion']);
    	$factura->setObservacion($entity['observacion']);
    	$factura->setProfesional($entity['profesional']);
    	$factura->setPyp($pyp);
    	$factura->setEstado('A');
    	$factura->setTipo('M');
    
    	$em->persist($factura);
    	$em->flush();
    
    	$this->get('session')->setFlash('ok', 'La factura ha sido registrada éxitosamente.');
    
    	return $this->redirect($this->generateUrl('facturacion_insumo_show', array("factura" => $factura->getId())));
    }
    
    
    public function showInsumoAction($factura)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    
    	if (!$factura) {
    		throw $this->createNotFoundException('La factura solicitada no esta disponible.');
    	}
    
    	$factura_imv = $em->getRepository('FacturacionBundle:FacturaImv')->findBy(array('factura' => $factura->getId()));
    	
    	if ($factura->getFarmacia()) {
    		$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->find($factura->getFarmacia());
    	}else{
    		$factura->setFarmacia(2);
    		$em->persist($factura);
    		$em->flush();
    		
    		$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->find($factura->getFarmacia());
    	}

    	if($factura->getPyp()){
    		$pyp = $em->getRepository('ParametrizarBundle:Pyp')->find($factura->getPyp());
    
    		$dql = $em->createQuery( "SELECT
										i.id,
    									i.nombre
									 FROM
										FarmaciaBundle:ImvPyp ip
									 JOIN
										ip.imv i
									 WHERE
    									ip.pyp = :categoria
									 ORDER BY
										i.nombre ASC");
    
    		$dql->setParameter('categoria', $pyp->getId());
    
    		$consultas = $dql->getResult();
    
    	}else{
    		$pyp = "";
    
    		$dql = $em->createQuery( "SELECT
										i.id,
    									i.nombre
									 FROM
										ParametrizarBundle:ImvContrato ic
									 JOIN
										ic.imv i
    								 JOIN
    									ic.contrato ct
    								 JOIN
    									ct.cliente cli
									 WHERE										
    									cli.id = :cliente
									 ORDER BY
										i.nombre ASC");
    
    		$dql->setParameter('cliente', $factura->getCliente()->getId());
    
    		$consultas = $dql->getResult();
    	}
    
    	if($factura->getProfesional()){
    		$profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
    	}else{
    		$profesional = "";
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_insumo_new", array('tipo' => 'A')));
    
    	return $this->render('FacturacionBundle:Factura:show_insumo.html.twig', array(
    			'factura'  => $factura,
    			'imv' => $factura_imv,
    			'pyp' => $pyp,
    			'consultas' => $consultas,
    			'profesional' => $profesional,
    			'farmacia' => $farmacia
    	));
    }
    
    public function urgenciasListAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Urgencias");
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$dql = $em->createQuery( "SELECT
										f
									 FROM
										FacturacionBundle:Factura f
									 WHERE
										f.estado =  'A' AND
    									f.tipo = 'U' OR
    									f.tipo = 'H'
									 ORDER BY
										f.fecha ASC");
    	
    	$factura = $dql->getResult();
    	
    	return $this->render('FacturacionBundle:Factura:urgencias_list.html.twig', array(
    			'facturas'  => $factura
    	));
    }
    
    
    public function urgenciasPrintAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Urgencias");
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$dql = $em->createQuery( "SELECT
										f
                                                                                
									 FROM
										FacturacionBundle:Factura f
                                                                                
                                                                     
                                                                                
									 WHERE
										f.estado =  'A' AND
    									f.tipo = 'U' OR
    									f.tipo = 'H'
									 ORDER BY
										f.fecha ASC");
    	
    	$factura = $dql->getResult();
        
       
    	
    	
    	return $this->render('FacturacionBundle:Factura:urgencias_print.html.twig', array(
    			'facturas'  => $factura,
    	));
    }
    
    
    public function facturaPrintAction($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
    	
                $factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    	
                if (!$factura) {
                        throw $this->createNotFoundException('La factura solicitada no existe');
                }
                
               
	    		if($factura->getTipo() == 'H'){
	    			
	    			$factura->setEstado('C');

	    			
	    		}elseif($factura->getTipo() == 'U'){
	    			
	    			$factura->setEstado('C');

	    			
	    		}
                        
                        $em->persist($factura);
	    		$em->flush(); 
                $factura_cargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findoneBy(array('factura' => $factura->getId()));
                
                if ( $factura_cargo->getAmbito()=='3' ){

                    
                    $factura_cargo->setEstado('C');
                }
                 
                $em->persist($factura_cargo);
                $em->flush();
                
                
                $factura_cargo1 = $em->getRepository('FacturacionBundle:FacturaCargo')->findBy(array('factura' => $factura->getId()));    	

                $factura_imv = $em->getRepository('FacturacionBundle:FacturaImv')->findBy(array('factura' => $factura->getId()));    	
                //$historia = $em->getRepository('HisotoriaBundle:Hc')->findBy(array('factura' => $factura->getId()));

                $mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($factura->getPaciente()->getMupio());
                
                // se consulta por la informacion del profesional para ser visulizada en la factura.
                $profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
                $factura->setProfesional($profesional->getNombre().' '.$profesional->getApellido());

                $pdf = $this->get('white_october.tcpdf')->create();
               
                
                
                
                  
                $html = $this->renderView('FacturacionBundle:Factura:facturau.pdf.twig',array(
                                                                        'factura' => $factura,
                                                                        'cargos' => $factura_cargo1,
                                                                        'imvs' => $factura_imv,
                                                                        'mupio' => $mupio
                ));
                

                return $pdf->quick_pdf($html, 'factura_venta_'.$factura->getId().'.pdf', 'D');  
}

    public function facturaShowAction($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
    	
                $factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    	
                if (!$factura) {
                        throw $this->createNotFoundException('La factura solicitada no existe');
                }
    	
                $factura_cargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findBy(array('factura' => $factura->getId()));    	
                $factura_imv = $em->getRepository('FacturacionBundle:FacturaImv')->findBy(array('factura' => $factura->getId()));    	

                $mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($factura->getPaciente()->getMupio());

                // se consulta por la informacion del profesional para ser visulizada en la factura.
                $profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
                $factura->setProfesional($profesional->getNombre().' '.$profesional->getApellido());

                $pdf = $this->get('white_october.tcpdf')->create();
                
                $html = $this->renderView('FacturacionBundle:Factura:facturau.pdf.twig',array(
                                                                        'factura' => $factura,
                                                                        'cargos' => $factura_cargo,
                                                                        'imvs' => $factura_imv,
                                                                        'mupio' => $mupio
                ));
                
                

                return $pdf->quick_pdf($html,'factura_venta_'.$factura->getId().'.pdf', 'I');  
}
    
    /**
     * @uses Función que consulta la información del paciente por tipo y número de identificación.
     *
     * @param ninguno
     */
    public function jxBuscarPacienteAction() {
    
    	$request = $this->get('request');
    	
    	$tipoid = $request->request->get('tipoid');
    	$identificacion = $request->request->get('identificacion');
    	    	
    	if(is_numeric($identificacion)){
    	
    		$em = $this->getDoctrine()->getEntityManager();
    		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->findOneBy(array('tipoId' => $tipoid, 'identificacion' => $identificacion));
    	
    		if($paciente){
    			$cliente = $em->getRepository('ParametrizarBundle:Afiliacion')->findBy(array('paciente' => $paciente->getId()));
    			 
    			$response=array("responseCode" => 200,
    					"id" => $paciente->getId(),
    					"nombre" => ucwords($paciente->getPriNombre()." ".$paciente->getSegNombre()." ".$paciente->getPriApellido()." ".$paciente->getSegApellido()),
    					"nacimiento" => $paciente->getFN()->format('d-m-Y'),
    					"edad" => $paciente->getEdad(),
    					"sexo" => $paciente->getSexo(),
    					"creado" => $paciente->getCreated()->format('d-m-Y'));
    	
    			foreach($cliente as $value)
    			{
    				$response['cliente'][$value->getCliente()->getId()] = $value->getCliente()->getNombre();
    			}
    	
    		}
    		else{
    			$response=array("responseCode"=>400, "msg"=>"el paciente no existe en el sistema!");
    		}
    	}else{
    		$response=array("responseCode"=>400, "msg"=>"Por favor ingrese un valor valido.");
    	}
    	
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));    
    }
    
    
    
    
    /**
     * @uses Función que almacena un cargo de una factura.
     *
     * @param ninguno
     */
    public function jxCargoSaveAction() {
    
    	$request = $this->get('request');
    	 
    	$factura = $request->request->get('factura');
    	$cargo = $request->request->get('cargo');
    	$cantidad = $request->request->get('cantidad');
    	$vrUnitario = $request->request->get('vr_unitario');
    	$vrFacturado = $request->request->get('vr_facturado');
    	$cobrarPte = $request->request->get('cobrar_pte');
    	$pagoPte = $request->request->get('pago_pte');
    	$recoIps = $request->request->get('cargo_ips');
    	$valorTotal = $request->request->get('total');
    	$estado = $request->request->get('estado');

    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$f_c = $em->getRepository('FacturacionBundle:FacturaCargo')->findBy(array('factura' => $factura, 'cargo' => $cargo));

    	if (!$f_c){    		
    	    	
	    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
	    	$cargo = $em->getRepository('ParametrizarBundle:Cargo')->find($cargo);
	    	
	    	$factura_cargo = new FacturaCargo();
	    		 
	    	if($factura && $cargo){
	    			
	    		$factura_cargo->setFactura($factura);
	    		$factura_cargo->setCargo($cargo);
	    		$factura_cargo->setCantidad($cantidad);
	    		$factura_cargo->setVrUnitario($vrUnitario);
	    		$factura_cargo->setVrFacturado($cantidad*$vrUnitario);
	    		$factura_cargo->setCobrarPte($cobrarPte);
	    		$factura_cargo->setPagoPte($pagoPte);
	    		$factura_cargo->setRecoIps($recoIps);
	    		$factura_cargo->setValorTotal(((($cantidad*$vrUnitario)-$cobrarPte)));
	    		
	    		if (trim($estado)){
	    			$factura_cargo->setEstado($estado);
	    		}else{
	    			$factura_cargo->setEstado('A');
	    		}
	    		
	    		
	    		if($factura->getTipo() == 'U'){
	    			
	    			$factura_cargo->setAmbito(3);
	    			$factura->setEstado('A');
	    			
	    		}elseif($factura->getTipo() == 'H'){
	    			
	    			$factura_cargo->setAmbito(2);
	    			$factura->setEstado('A');
	    			
	    		}elseif($factura->getTipo() == 'C'){
	    			
	    			$factura_cargo->setAmbito(1);
	    			$factura->setEstado('A');
	    			
	    		}else{
	    			$factura->setEstado('C');
	    			$factura_cargo->setAmbito(1);
	    		}
	    		
	    		$em->persist($factura_cargo);
	    		$em->persist($factura);
	    		$em->flush();    			
	    
	    		$response=array("responseCode" => 200, 
	    						"msg" => 'La actividad se ha cargado correctamente.',
	    						"codigo" => $cargo->getCups(),
	    						"nombre" => $cargo->getNombre(),
	    						"cantidad" => $factura_cargo->getCantidad(),
	    						"unitario" => $factura_cargo->getVrUnitario(),
	    						"cobro" => $factura_cargo->getCobrarPte(),
	    						"total" => $factura_cargo->getValorTotal());    			 
	    	}
	    	else{
	    		$response=array("responseCode"=>400, "msg"=>"La actividad no se ha cargado.");
	    	}
    	}else {
    		$response=array("responseCode"=>400, "msg"=>"La actividad ya ha sido cargada.");
    	}   	
	    	 
	    	$return=json_encode($response);
	    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
    
    
    public function imprimirAction($factura) {
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    	
    	
    	if (!$factura) {
    		throw $this->createNotFoundException('La factura solicitada no existe');
    	}
        
        if($factura->getTipo() == 'C'){
	    			
	    			
	    			$factura->setEstado('A');
	    			
	    		}elseif($factura->getTipo() == 'H'){
	    			
	    			$factura->setEstado('A');

	    			
	    		}elseif($factura->getTipo() == 'U'){
	    			
	    			$factura->setEstado('A');

	    			
	    		}else{
	    			$factura->setEstado('C');
	    		}
                        
                        $em->persist($factura);
	    		$em->flush();  
    	
    	$factura_cargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findBy(array('factura' => $factura->getId()));    	
    	$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($factura->getPaciente()->getMupio());
    	
    	// se consulta por la informacion del profesional para ser visulizada en la factura.
    	$profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
    	$factura->setProfesional($profesional->getNombre().' '.$profesional->getApellido());
    	
    	$pdf = $this->get('white_october.tcpdf')->create();
    	
    	$html = $this->renderView('FacturacionBundle:Factura:factura.pdf.twig',array(
    								'factura' => $factura,
    								'cargos' => $factura_cargo,
    								'mupio' => $mupio
    	));
    	
    	return $pdf->quick_pdf($html, 'factura_venta_'.$factura->getId().'.pdf', 'D');    	    	
    }
    
    
    public function imprimirImvAction($factura) {
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    	 
    	if (!$factura) {
    		throw $this->createNotFoundException('La factura solicitada no existe');
    	}else{
    
    		$factura->setEstado('C');
    
    		$em->persist($factura);
    		$em->flush();
    
    	}
    	 
    	$factura_imv = $em->getRepository('FacturacionBundle:FacturaImv')->findBy(array('factura' => $factura->getId()));
    	 
    	$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($factura->getPaciente()->getMupio());
    	 
    	$pdf = $this->get('white_october.tcpdf')->create();
    	 
    	$html = $this->renderView('FacturacionBundle:Factura:factura_medicamento.pdf.twig',array(
    			'factura' => $factura,
    			'imvs' => $factura_imv,
    			'mupio' => $mupio
    	));
    	 
    	return $pdf->quick_pdf($html, 'factura_venta_'.$factura->getId().'.pdf', 'D');
    	 
    }
    
    
    /**
     * @uses Función que elimina un cargo de una factura abierta.
     *
     * @param ninguno
     */
    public function jxCargoDeleteAction() {
    
    	$request = $this->get('request');
    
    	$factura = $request->request->get('factura');
    	$cargo = $request->request->get('cargo');
    	
    
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$f_c = $em->getRepository('FacturacionBundle:FacturaCargo')->findOneBy(array('factura' => $factura, 'cargo' => $cargo));
    
    	if ($f_c){
    
    		$em->remove($f_c);
    		$em->flush();
    			 
   			$response=array("responseCode" => 200, "msg" => 'La actividad se ha eliminado correctamente.');
    	}
    	else{
    		$response=array("responseCode"=>400, "msg"=>"La actividad no existe en el sistema.");
    	}
    	
    	 
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
    
    
    /**
     * @uses Función que almacena un insumo de una factura.
     *
     * @param ninguno
     */
	public function jxImvSaveAction() {

    	$request = $this->get('request');
    
    	$factura = $request->request->get('factura');
    	$imv = $request->request->get('imv');
    	$cantidad = $request->request->get('cantidad');
    	$vrUnitario = $request->request->get('vr_unitario');
    	$vrFacturado = $request->request->get('vr_facturado');
    	$cobrarPte = $request->request->get('cobrar_pte');
    	$pagoPte = $request->request->get('pago_pte');
    	$recoIps = $request->request->get('cargo_ips');
    	$valorTotal = $request->request->get('total');
    	$estado = $request->request->get('estado');
    
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$f_i = $em->getRepository('FacturacionBundle:FacturaImv')->findOneBy(array('factura' => $factura, 'imv' => $imv));
    
    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find($imv);    	
    	
    	if (!$f_i){
    		
    		$factura_imv = new FacturaImv();
    		 
    		if($factura && $imv){
    				
    			$imv_farmacia = $em->getRepository('FarmaciaBundle:ImvFarmacia')->findOneBy(array('farmacia' => $factura->getFarmacia(), 'imv' => $imv));
    			 
    			$factura_imv->setFactura($factura);
    			$factura_imv->setImv($imv);
    			$factura_imv->setCantidad($cantidad);
    			$factura_imv->setVrUnitario($vrUnitario);
    			$factura_imv->setVrFacturado($vrUnitario*$cantidad);
    			$factura_imv->setCobrarPte($cobrarPte);
    			$factura_imv->setPagoPte($pagoPte);
    			$factura_imv->setRecoIps($recoIps);
    			$factura_imv->setValorTotal((($cantidad*$vrUnitario)-$cobrarPte));
    			 
    			if (trim($estado)){
    				$factura_imv->setEstado($estado);
    			}else{
    				$factura_imv->setEstado('A');
    			}
    			 
    			if($factura->getTipo() == 'U'){
    				 
    				$factura->setEstado('A');
    				 
    			}elseif($factura->getTipo() == 'H'){
    				 
    				$factura->setEstado('A');
    				 
    			}else{
    				$factura->setEstado('C');
    			}
    				
    			$imv->setCantT($imv->getCantT()-$cantidad);
    			$imv_farmacia->setCant($imv_farmacia->getCant()-$cantidad);
    			 
    			$em->persist($factura_imv);
    			$em->persist($factura);
    			$em->persist($imv);
    			$em->persist($imv_farmacia);
    			$em->flush();
    			 
    			$response=array("responseCode" => 200,
    					"msg" => 'La actividad se ha cargado correctamente.',
    					"codigo" => $imv->getCodCups(),
    					"nombre" => $imv->getNombre(),
    					"cantidad" => $factura_imv->getCantidad(),
    					"unitario" => $factura_imv->getVrUnitario(),
    					"cobro" => $factura_imv->getCobrarPte(),
    					"total" => $factura_imv->getValorTotal());
    		
    		}else{
    			$response=array("responseCode"=>400, "msg"=>"La actividad no se ha cargado.");
    		}   		
		}else {
			
			if ($factura->getTipo() == 'U') {
				 
				$f_i->setCantidad($f_i->getCantidad()+$cantidad);
				$f_i->setVrUnitario($vrUnitario);
				$f_i->setCobrarPte($f_i->getCobrarPte()+$cobrarPte);
				$f_i->setPagoPte($f_i->getPagoPte()+$pagoPte);
				$f_i->setRecoIps($f_i->getRecoIps()+$recoIps);
				$f_i->setVrFacturado($f_i->getVrFacturado()+($vrFacturado*$cantidad));
				$f_i->setValorTotal($f_i->getValorTotal()+($valorTotal));

				$em->persist($f_i);
				$em->flush();
				
				$response=array("responseCode" => 200,
    					"msg" => 'La actividad se ha cargado correctamente.',
    					"codigo" => $imv->getCodCups(),
    					"nombre" => $imv->getNombre(),
    					"cantidad" => $f_i->getCantidad(),
    					"unitario" => $f_i->getVrUnitario(),
    					"cobro" => $f_i->getCobrarPte(),
    					"total" => $f_i->getValorTotal());
				 
			}else{
				$response=array("responseCode"=>400, "msg"=>"La actividad ya ha sido cargada.");
			}    		
    	}
    	 
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
    
    
    /**
     * @uses Función que elimina un cargo de una factura abierta.
     *
     * @param ninguno
     */
    public function jxImvDeleteAction() {
    
    	$request = $this->get('request');
    
    	$factura = $request->request->get('factura');
    	$imv = $request->request->get('imv');
    	 
    
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$f_i = $em->getRepository('FacturacionBundle:FacturaImv')->findOneBy(array('factura' => $factura, 'imv' => $imv));
    
    	if ($f_i){
    
    		$em->remove($f_i);
    		$em->flush();
    
    		$response=array("responseCode" => 200, "msg" => 'La actividad se ha eliminado correctamente.');
    	}
    	else{
    		$response=array("responseCode"=>400, "msg"=>"La actividad no existe en el sistema.");
    	}
    	 
    
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
    
    public function urgenciasImvListAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Urgencias");
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$dql = $em->createQuery( "SELECT
										f
									 FROM
										FacturacionBundle:Factura f
									 WHERE
										f.estado = 'A' AND
    									f.tipo = 'U' OR
    									f.tipo = 'H'
									 ORDER BY
										f.fecha ASC");
    	 
    	$factura = $dql->getResult();
    	 
    	return $this->render('FacturacionBundle:Factura:urgencias_imv_list.html.twig', array(
    			'facturas'  => $factura
    	));
    }
    
    public function ripsAction($factura)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$entity = $em->getRepository("FacturacionBundle:FacturaFinal")->find($factura);
    	 
    	if (!$entity) {
    		throw $this->createNotFoundException('La información solicitada no esta disponible.');
    	}
    	 
    	$cliente = $entity->getCliente();
    	 
    	$dir = $this->container->getParameter('knx.directorio.rips');
    	 
    	
    	$f_inicio = $entity->getInicio()->format("Y-m-d");
    	$f_fin = $entity->getFin()->format("Y-m-d");

    	$factura = $entity->getId();
    
   		// Se eliminan todos los archivos txt que se encuentran la carpeta para crear unos nuevos
   		exec("rm -rf ".$dir."src/*.txt");
    
   		$us = $this->fileUS($cliente, $f_inicio, $f_fin);
   		$ac = $this->fileAC($cliente, $f_inicio, $f_fin);
   		$ap = $this->fileAP($cliente, $f_inicio, $f_fin);
   		$af = $this->fileAF($cliente, $f_inicio, $f_fin);
   		$ad = $this->fileAD($cliente, $f_inicio, $f_fin);
   		$am = $this->fileAM($cliente, $f_inicio, $f_fin);
   		$at = $this->fileAT($cliente, $f_inicio, $f_fin);
    
   		$this->fileCt($us, $ap, $ac, $ad, $af, $am, $at, $f_fin);
    
   		// Genaramos la el nombre que contendra el archivo comprimido
   		$nameFile = 'san-agustin-rips-'.$entity->getId().".zip";
   		// Comprimimos los archivos con un nombre unico.
   		exec("zip -j ".$dir.$nameFile." ".$dir."src/*");
    
   		$this->downloadRips($entity->getId());
    	
    	$this->get('session')->setFlash('ok', 'Los RIPS han sido generados éxitosamente.');
    	
    	return $this->redirect($this->generateUrl('factura_final_show', array("id" => $factura)));
    }
    
    private function fileUS($cliente, $f_inicio, $f_fin){
    	 
    	$dir = $this->container->getParameter('knx.directorio.rips')."src/";
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$dql= " SELECT
    				DISTINCT
    				p.identificacion AS id,
			    	p.tipoId,
			    	p.priApellido,
			    	p.segApellido,
			    	p.priNombre,
			    	p.segNombre,
			    	p.fN,
			    	p.sexo,
			    	p.depto,
			    	p.mupio,
			    	p.zona,
			    	cli.codigo
		    	FROM
		    		FacturacionBundle:Factura f
		    	JOIN
		    	   	f.paciente p
		    	JOIN
		    		f.cliente cli
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente
		    	ORDER BY
		    		f.fecha ASC";
    
    	$query = $em->createQuery($dql);
    
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    
    	$entity = $query->getArrayResult();
    	
    	$depto = $em->getRepository('ParametrizarBundle:Depto')->findAll();
    	$mupio = $em->getRepository('ParametrizarBundle:Mupio')->findAll();

    	foreach ($depto as $value){
    		$dus[$value->getId()] = $value->getCodigo();
    	}
    	
    	foreach ($mupio as $value){
    		$mus[$value->getId()] = $value->getCodigo();
    	}

    	$periodo = explode("-", $f_fin);
    	$subfijo = $periodo[1].$periodo[0];
    	 
    	$gestor = fopen($dir."US".$subfijo.".txt", "w+");
    	 
    	if (!$gestor){
    		$this->get('session')->setFlash('info', 'No se puede crear txt.');
    		return $this->redirect($this->generateUrl('factura_cliente_list'));
    	}
    	 
    	$date2 = new \DateTime('now');
    	 
    	foreach ($entity as $value){
    		$fn = new \DateTime($value['fN']->format('Y-m-d'));
    		$interval = $fn->diff($date2);
    		fwrite($gestor, "".$value['tipoId'].",".$value['id'].",".$value['codigo'].",1,".$value['priApellido'].",".$value['segApellido'].",".$value['priNombre'].",".$value['segNombre'].",".$interval->format('%y').",1,".$value['sexo'].",".$dus[$value['depto']].",".$mus[$value['mupio']].",".$value['zona']."\r\n");
    	}
    	 
    	return count($entity);
    }
    
    private function fileAC($cliente, $f_inicio, $f_fin){
    
    	$dir = $this->container->getParameter('knx.directorio.rips')."src/";
    
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find(1);
    
    	$dql= " SELECT
			    	f.id AS factura,
    				p.identificacion AS id,
			    	p.tipoId,
			    	f.fecha,
			    	f.autorizacion,
    				f.pyp,
    				c.id AS cargo,
			    	c.cups,
    				c.tipoCons,
			    	fc.vrUnitario,
			    	fc.pagoPte
		    	FROM
		    		FacturacionBundle:FacturaCargo fc
		    	JOIN
		    		fc.factura f
    			JOIN
		    		f.paciente p
		    	JOIN
		    		fc.cargo c
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente AND
			    	c.rips = :rips
		    	ORDER BY
		    		f.fecha ASC";
    
    	$query = $em->createQuery($dql);
    
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    	$query->setParameter('rips', 'AC');
    
    	$entity = $query->getArrayResult();
    	 
    	$periodo = explode("-", $f_fin);
    	$subfijo = $periodo[1].$periodo[0];
    
    	$gestor = fopen($dir."AC".$subfijo.".txt", "w+");
    
    	if (!$gestor){
    		$this->get('session')->setFlash('info', 'No se puede crear txt.');
    		return $this->redirect($this->generateUrl('factura_cliente_list'));
    	}
    	
    	$dql= " SELECT
			    	f.id,
    				h.causaExt,
    				c.codigo,
    				h.tipoDx
		    	FROM
		    		HistoriaBundle:HcDx hdx
		    	JOIN
		    		hdx.hc h
    			JOIN
		    		hdx.cie c
    			JOIN
		    		h.factura f
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente
		    	ORDER BY
		    		f.fecha ASC";
    	
    	$query = $em->createQuery($dql);
    	
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    	
    	$hc = $query->getArrayResult();
    	
    	
    	//Consulta para obtener los cargos de pyp
    	$dql= " SELECT
			    	c.id AS cargo,
    				p.id AS pyp,
    				cp. tipoCons
		    	FROM
		    		ParametrizarBundle:CargoPyp cp
		    	JOIN
		    		cp.cargo c
    			JOIN
		    		cp.pyp p
		    	WHERE
			    	c.rips = :rips";
    	 
    	$query = $em->createQuery($dql);
    	 
    	$query->setParameter('rips', 'AC');
    	
    	$cp = $query->getArrayResult();
    
    	foreach ($entity as $value){
    		
    		$num_dx = 0;
    		$dx = "";
    		$tdx = "";
    		$ce = "";

    		if (trim($value['pyp'])){
    			foreach ($cp as $c){
    				if ($c['cargo'] == $value['cargo'] && $c['cargo'] == $value['cargo']) {
    					$finalidad = $c['tipoCons'];
    				}
    			}
    		}else{
    			$finalidad = $value['tipoCons'];
    		}    		
    		
    		foreach ($hc as $h){
    			if ($h['id'] == $value['factura']) {
    				$ce = $h['causaExt'];
    				$tdx = $h['tipoDx'];
    				$dx .= $h['codigo'].",";
    				$num_dx++;
    			}
    		}
    		
    		if($num_dx == 0){
    			$dx .=",,,,";
    		}elseif($num_dx == 1){
    			$dx .=",,,";
    		}elseif($num_dx == 2){
    			$dx .=",,";
    		}elseif($num_dx == 3){
    			$dx .=",";
    		}
    
    		$fecha = new \DateTime($value['fecha']->format('Y-m-d'));
    
    		fwrite($gestor, "".$value['factura'].",".$empresa->getHabilitacion().",".$value['tipoId'].",".$value['id'].",".$fecha->format('d/m/Y').",".$value['autorizacion'].",".$value['cups'].",".$finalidad.",".$ce.",".$dx.$tdx.",".$value['vrUnitario'].".00,".$value['pagoPte'].".00,".($value['vrUnitario']-$value['pagoPte']).".00\r\n");
    	}
    
    	return count($entity);
    }
    
    private function fileAP($cliente, $f_inicio, $f_fin){
    
    	$dir = $this->container->getParameter('knx.directorio.rips')."src/";
    
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find(1);
    
    	$dql= " SELECT
			    	p.identificacion AS id,
			    	p.tipoId,
    				f.id AS factura,
			    	f.fecha,
			    	f.autorizacion,
    				f.pyp,
			    	c.cups,
    				c.id AS cargo,
			    	fc.vrUnitario,
    				fc.ambito,
    				fc.cantidad,
    				c.tipoProc
		    	FROM
		    		FacturacionBundle:FacturaCargo fc
		    	JOIN
		    		fc.factura f
    			JOIN
		    		f.paciente p    			
		    	JOIN
		    		fc.cargo c
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente AND
			    	c.rips = :rips
		    	ORDER BY
		    		f.fecha ASC";
    
    	$query = $em->createQuery($dql);
    
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    	$query->setParameter('rips', 'AP');
    
    	$entity = $query->getArrayResult();
    	 
    	$periodo = explode("-", $f_fin);
    	$subfijo = $periodo[1].$periodo[0];
    
    	$gestor = fopen($dir."AP".$subfijo.".txt", "w+");
    
    	if (!$gestor){
    		$this->get('session')->setFlash('info', 'No se puede crear txt.');
    		return $this->redirect($this->generateUrl('factura_cliente_list'));
    	}
    	
    	//Consulta para obtener los cargos de pyp
    	$dql= " SELECT
			    	c.id AS cargo,
    				p.id AS pyp,
    				cp. tipoProc
		    	FROM
		    		ParametrizarBundle:CargoPyp cp
		    	JOIN
		    		cp.cargo c
    			JOIN
		    		cp.pyp p
		    	WHERE
			    	c.rips = :rips";
    	
    	$query = $em->createQuery($dql);
    	
    	$query->setParameter('rips', 'AP');
    	 
    	$cp = $query->getArrayResult();
    	
    	$num_registros = 0;
    
    	foreach ($entity as $value){
    		
    		if (trim($value['pyp'])){
    			foreach ($cp as $c){
    				if ($c['cargo'] == $value['cargo'] && $c['cargo'] == $value['cargo']) {
    					$finalidad = $c['tipoProc'];
    				}
    			}
    		}else{
    			$finalidad = $value['tipoProc'];
    		}
    
    		if ($value['cantidad'] == 1) {
    			
    			$num_registros += 1;
    			$fecha = new \DateTime($value['fecha']->format('Y-m-d'));
    			fwrite($gestor, "".$value['factura'].",".$empresa->getHabilitacion().",".$value['tipoId'].",".$value['id'].",".$fecha->format('d/m/Y').",".$value['autorizacion'].",".$value['cups'].",".$value['ambito'].",".$finalidad.",,,,,,".$value['vrUnitario']."\r\n");
    			
    		}else{

    			$fecha = new \DateTime($value['fecha']->format('Y-m-d'));
    			
    			for($i = 1; $i <= $value['cantidad']; $i++){
    				
    				$num_registros += 1;    				
    				fwrite($gestor, "".$value['factura'].",".$empresa->getHabilitacion().",".$value['tipoId'].",".$value['id'].",".$fecha->format('d/m/Y').",".$value['autorizacion'].",".$value['cups'].",".$value['ambito'].",".$finalidad.",,,,,,".$value['vrUnitario']."\r\n");
    			}
    		}    		
    	}
    
    	return $num_registros;
    }
    
    private function fileAM($cliente, $f_inicio, $f_fin){
    
    	$dir = $this->container->getParameter('knx.directorio.rips')."src/";
    
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find(1);
    
    	$dql= " SELECT
			    	p.identificacion AS id,
			    	p.tipoId,
    				p.fN,
    				f.id AS factura,
			    	f.fecha,
			    	f.autorizacion,
    				f.pyp,
    				c.codigo as cliente,
			    	i.codAdmin,
    				i.nombre,
    				i.formaFarmaceutica,
    				i.concentracion,
    				i.uniMedida,
    				fi.cantidad,
			    	fi.vrUnitario,
    				fi.valorTotal
		    	FROM
		    		FacturacionBundle:FacturaImv fi
		    	JOIN
		    		fi.factura f
    			JOIN
		    		f.paciente p
		    	JOIN
		    		fi.imv i
    			JOIN
		    		f.cliente c
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente AND
    				f.tipo = 'M' AND
    				i.tipoImv = 'M'
		    	ORDER BY
		    		f.fecha ASC";
    
    	$query = $em->createQuery($dql);
    
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    
    	$entity = $query->getArrayResult();
    
    	$periodo = explode("-", $f_fin);
    	$subfijo = $periodo[1].$periodo[0];
    
    	$gestor = fopen($dir."AM".$subfijo.".txt", "w+");
    
    	if (!$gestor){
    		$this->get('session')->setFlash('info', 'No se puede crear txt.');
    		return $this->redirect($this->generateUrl('factura_cliente_list'));
    	}    	 
    	    	 
    	$num_registros = 0;
    
    	foreach ($entity as $value){
    		
    		$num_registros += 1;
    		fwrite($gestor, "".$value['factura'].",".$empresa->getHabilitacion().",".$value['tipoId'].",".$value['id'].",".$value['autorizacion'].",".$value['codAdmin'].",1,".$value['nombre'].",".$value['formaFarmaceutica'].",".$value['concentracion'].",".$value['uniMedida'].",".$value['cantidad'].",".$value['vrUnitario'].",".$value['valorTotal']."\r\n");

    	}
    
    	return $num_registros;
    }
    
    private function fileAT($cliente, $f_inicio, $f_fin){
    
    	$dir = $this->container->getParameter('knx.directorio.rips')."src/";
    
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find(1);
    
    	$dql= " SELECT
			    	p.identificacion AS id,
			    	p.tipoId,
    				p.fN,
    				f.id AS factura,
			    	f.fecha,
			    	f.autorizacion,
    				f.pyp,
    				c.codigo as cliente,
			    	i.cums,
    				i.nombre,
    				fi.cantidad,
			    	fi.vrUnitario,
    				fi.valorTotal
		    	FROM
		    		FacturacionBundle:FacturaImv fi
		    	JOIN
		    		fi.factura f
    			JOIN
		    		f.paciente p
		    	JOIN
		    		fi.imv i
    			JOIN
		    		f.cliente c
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente AND
    				f.tipo = 'M' AND
    				i.tipoImv = 'I'
		    	ORDER BY
		    		f.fecha ASC";
    
    	$query = $em->createQuery($dql);
    
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    
    	$entity = $query->getArrayResult();
    
    	$periodo = explode("-", $f_fin);
    	$subfijo = $periodo[1].$periodo[0];
    
    	$gestor = fopen($dir."AT".$subfijo.".txt", "w+");
    
    	if (!$gestor){
    		$this->get('session')->setFlash('info', 'No se puede crear txt.');
    		return $this->redirect($this->generateUrl('factura_cliente_list'));
    	}
    	 
    	$num_registros = 0;
    
    	foreach ($entity as $value){
    
    		$num_registros += 1;
    		fwrite($gestor, "".$value['factura'].",".$empresa->getHabilitacion().",".$value['tipoId'].",".$value['id'].",".$value['autorizacion'].",1,".$value['cums'].",".$value['nombre'].",".$value['cantidad'].",".$value['vrUnitario'].",".$value['valorTotal']."\r\n");
    	}
    
    	return $num_registros;
    }
    
    private function fileAF($cliente, $f_inicio, $f_fin){
    
    	$dir = $this->container->getParameter('knx.directorio.rips')."src/";
    
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find(1);
    
    	$dql= " SELECT
			    	f.id AS factura,
    				f.fecha,
    				p.identificacion AS id,
			    	p.tipoId,
    				SUM (fc.vrFacturado) AS valor,
			    	SUM (fc.pagoPte) AS copago,
                    SUM (fc.recoIps) AS ips
		    	FROM
		    		FacturacionBundle:FacturaCargo fc
    			JOIN
    				fc.factura f
    			JOIN
    				f.paciente p
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente 
    			GROUP BY
    				f.id";
    
    	$query = $em->createQuery($dql);
    
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    
    	$entity = $query->getArrayResult();
    	
    	$dql= " SELECT
			    	f.id AS factura,
    				f.fecha,
    				p.identificacion AS id,
			    	p.tipoId,
    				SUM (fi.vrFacturado) AS valor,
			    	SUM (fi.pagoPte) AS copago,
                    SUM (fi.recoIps) AS ips
		    	FROM
		    		FacturacionBundle:FacturaImv fi
    			JOIN
    				fi.factura f
    			JOIN
    				f.paciente p
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente
    			GROUP BY
    				f.id";
    	
    	$query = $em->createQuery($dql);

    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    	
    	$imv = $query->getArrayResult();
    	
    	foreach ($imv as $value){
    		$entity[] = $value;
    	}
    	 
    	sort($entity);
    	 
    	$periodo = explode("-", $f_fin);
    	$subfijo = $periodo[1].$periodo[0];
    
    	$gestor = fopen($dir."AF".$subfijo.".txt", "w+");
    
    	if (!$gestor){
    		$this->get('session')->setFlash('info', 'No se puede crear txt.');
    		return $this->redirect($this->generateUrl('factura_cliente_list'));
    	}
    	 
    	$fecha = new \DateTime('now');
    	$inicio = new \DateTime($f_inicio);
    	$fin = new \DateTime($f_fin);
    	
    	$beneficios = array('1' => 'Contributivo','2' => 'Subsidiado','3' => 'Vinculado','4' => 'Particular', '5' => 'Otro');
    	
    	foreach ($entity as $value){
    		
    		$fecha = new \DateTime($value['fecha']->format('Y-m-d'));
    		
    		fwrite($gestor, "".$empresa->getHabilitacion().",".$empresa->getNombre().",NI,".$empresa->getNit().",".$value['factura'].",".$fecha->format('d/m/Y').",".$inicio->format('d/m/Y').",".$fin->format('d/m/Y').",".$cliente->getCodigo().",".$cliente->getNombre().",,".$beneficios[$cliente->getTipo()].",,".($value['copago']+$value['ips']).",0,0,".($value['valor']-$value['copago']-$value['ips'])."\r\n");
    	}
    
    	return count($entity);
    }
    
    private function fileAD($cliente, $f_inicio, $f_fin){
    
    	$dir = $this->container->getParameter('knx.directorio.rips')."src/";
    
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find(1);
    
    	$dql= " SELECT
			    	f.id AS factura,
			    	c.tipoCargo,
			    	SUM(fc.cantidad) AS cantidad,
			    	SUM(fc.valorTotal) AS valor
		    	FROM
		    		FacturacionBundle:FacturaCargo fc
		    	JOIN
		    		fc.factura f
    			JOIN
		    		fc.cargo c
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente
			    GROUP BY
    				c.tipoCargo, f.id";
    
    	$query = $em->createQuery($dql);
    
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    
    	$entity = $query->getArrayResult();
    	
    	$dql= " SELECT
			    	f.id AS factura,
			    	i.tipoImv as tipoCargo,
			    	SUM(fi.cantidad) AS cantidad,
			    	SUM(fi.valorTotal) AS valor
		    	FROM
		    		FacturacionBundle:FacturaImv fi
		    	JOIN
		    		fi.factura f
    			JOIN
		    		fi.imv i
		    	WHERE
			    	f.fecha > :inicio AND
			    	f.fecha <= :fin AND
			    	f.estado = :estado AND
			    	f.cliente = :cliente
			    GROUP BY
    				i.tipoImv, f.id";
    	
    	$query = $em->createQuery($dql);
    	
    	$query->setParameter('inicio', $f_inicio.' 00:00:00');
    	$query->setParameter('fin', $f_fin.' 23:59:00');
    	$query->setParameter('cliente', $cliente->getId());
    	$query->setParameter('estado', 'C');
    	
    	$medicamentos = $query->getArrayResult();
    	
    	foreach ($medicamentos as $value){
    		$entity[] = $value;
    	}
    	
    	sort($entity);
    	 
    	$periodo = explode("-", $f_fin);
    	$subfijo = $periodo[1].$periodo[0];
    
    	$gestor = fopen($dir."AD".$subfijo.".txt", "w+");
    
    	if (!$gestor){
    		$this->get('session')->setFlash('info', 'No se puede crear txt.');
    		return $this->redirect($this->generateUrl('factura_cliente_list'));
    	}
    	
    	$concepto = array('P' => '03','LB' => '02','CE' => '01','OS' => '09','M' => '12','I' => '09','V' => '09','MP' => '09');
    	
		foreach ($entity as $value){
			fwrite($gestor, "".$value["factura"].",".$empresa->getHabilitacion().",".$concepto[$value['tipoCargo']].",".$value["cantidad"].",0,".$value["valor"].".00\r\n");
    	}
    
    	return count($entity);
    }
    
    private function fileCt($us, $ap, $ac, $ad, $af, $am, $at, $f_fin){
    
    	$dir = $this->container->getParameter('knx.directorio.rips')."src/";
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find(1);
    	 
    	$periodo = explode("-", $f_fin);
    	$subfijo = $periodo[1].$periodo[0];
    
    	$gestor = fopen($dir."CT".$subfijo.".txt", "w+");
    
    	if (!$gestor){
    		$this->get('session')->setFlash('info', 'No se puede crear txt.');
    		return $this->redirect($this->generateUrl('factura_cliente_list'));
    	}
    		
    	$fecha = new \DateTime('now');
    	 
    	fwrite($gestor, "".$empresa->getHabilitacion().",".$fecha->format('d/m/Y').",US".$subfijo.",".$us."\r\n");
    	fwrite($gestor, "".$empresa->getHabilitacion().",".$fecha->format('d/m/Y').",AF".$subfijo.",".$af."\r\n");
    	fwrite($gestor, "".$empresa->getHabilitacion().",".$fecha->format('d/m/Y').",AD".$subfijo.",".$ad."\r\n");
    	fwrite($gestor, "".$empresa->getHabilitacion().",".$fecha->format('d/m/Y').",AC".$subfijo.",".$ac."\r\n");
    	fwrite($gestor, "".$empresa->getHabilitacion().",".$fecha->format('d/m/Y').",AP".$subfijo.",".$ap."\r\n");
    	fwrite($gestor, "".$empresa->getHabilitacion().",".$fecha->format('d/m/Y').",AM".$subfijo.",".$am."\r\n");
    	fwrite($gestor, "".$empresa->getHabilitacion().",".$fecha->format('d/m/Y').",AT".$subfijo.",".$at."\r\n");
    
    	return true;
    }
    
    private function downloadRips($factura_final)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$entity = $em->getRepository("FacturacionBundle:FacturaFinal")->find($factura_final);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('La información solicitada no esta disponible.');
    	}
    	 
    	$dir = $this->container->getParameter('knx.directorio.rips');
    	$nameFile = 'san-agustin-rips-'.$entity->getId().".zip";
    	$abririps = $dir.$nameFile;
    	 
    	header("Pragma: public");
    	header("Expires: 0");
    	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    	header("Cache-Control: private",false);
    	header("Content-Type: application/x-gzip");
    	header("Content-Disposition: attachment; filename=\"".basename($abririps)."\";" );
    	header("Content-Transfer-Encoding: binary");
    	header("Content-Length: ".filesize($abririps));
    	 
    	ob_clean();
    	flush();
    	readfile( $abririps );
    }
}