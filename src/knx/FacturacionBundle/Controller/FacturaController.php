<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FacturacionBundle\Entity\Factura;
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
    
    	$factura = new Factura();
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
    
    	$factura->setPaciente($paciente);
    	$factura->setCliente($cliente);
    	$factura->setServicio($servicio);
    	$factura->setUsuario($usuario);
    	$factura->setFecha(new \DateTime());
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
    
    	$factura = new Factura();
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
    
    	$factura->setPaciente($paciente);
    	$factura->setCliente($cliente);
    	$factura->setFarmacia($entity['farmacia']);
    	$factura->setUsuario($usuario);
    	$factura->setFecha(new \DateTime());
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
										i.tipoImv = 'MP' AND
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
										i.tipoImv != 'MP' AND
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
										f.estado = 'A' AND
    									f.tipo = 'U' OR
    									f.tipo = 'H'
									 ORDER BY
										f.fecha ASC");
    	
    	$factura = $dql->getResult();
    	
    	return $this->render('FacturacionBundle:Factura:urgencias_list.html.twig', array(
    			'facturas'  => $factura
    	));
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
	    		$factura_cargo->setVrFacturado($vrFacturado);
	    		$factura_cargo->setCobrarPte($cobrarPte);
	    		$factura_cargo->setPagoPte($pagoPte);
	    		$factura_cargo->setRecoIps($recoIps);
	    		$factura_cargo->setValorTotal((($cantidad*$vrUnitario)-$cobrarPte));
	    		
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
    	}else{    		    		
    		$factura->setEstado('C');    		
    		$em->persist($factura);
    		$em->flush();    		
    	}
    	
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
    			$factura_imv->setVrFacturado($vrFacturado);
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
				$f_i->setVrFacturado($f_i->getVrFacturado()+$vrFacturado);
				$f_i->setValorTotal($f_i->getValorTotal()+$valorTotal);
				 
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
}