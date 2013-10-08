<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FacturacionBundle\Entity\Factura;
use knx\FacturacionBundle\Form\FacturaType;

use knx\ParametrizarBundle\Form\AfiliacionType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use knx\FacturacionBundle\Entity\FacturaCargo;

class FacturaController extends Controller
{
    public function newConsultaAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_consulta_new"));
    	
    	$factura = new Factura();
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
		
		if($factura->getServicio() == 'CONSULTA EXTERNA'){
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
    	$ambito = $request->request->get('ambito');

    	$em = $this->getDoctrine()->getEntityManager();

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
    		$factura_cargo->setValorTotal($valorTotal);
    		$factura_cargo->setEstado('C');
    		$factura_cargo->setAmbito($ambito);
    		
    		if($factura->getTipo() == 'U'){
    			$factura->setEstado('A');
    		}else{
    			$factura->setEstado('C');
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
    	 
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
}