<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FacturacionBundle\Entity\Factura;
use knx\FacturacionBundle\Form\FacturaType;

use knx\ParametrizarBundle\Form\AfiliacionType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class FacturaController extends Controller
{
    public function newActividadAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_actividad_new"));
    	
    	$factura = new Factura();
    	$form = $this->createForm(new FacturaType(), $factura);
    	
    	$form_afiliacion = $this->createForm(new AfiliacionType()); 

    	return $this->render('FacturacionBundle:Factura:new.html.twig', array(
    			'form'   => $form->createView(),
    			'form_afiliacion' => $form_afiliacion->createView()
    	));
    }
    
    
    public function saveActividadAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_actividad_new"));
    	
    	$factura = new Factura();
    	
    	$form = $this->createForm(new FacturaType(), $factura);
    	$request = $this->getRequest();
    	$entity = $request->get($form->getName());

		$em = $this->getDoctrine()->getEntityManager();
		
		$paciente = $em->getRepository("ParametrizarBundle:Paciente")->findOneBy(array("identificacion" => $entity['paciente']));
		$cliente = $em->getRepository("ParametrizarBundle:Cliente")->find($entity['cliente']);
		$servicio = $em->getRepository("ParametrizarBundle:Servicio")->find($entity['servicio']);
		$usuario = $this->get('security.context')->getToken()->getUser();
		
		$factura->setFecha(new \DateTime());
		$factura->setAutorizacion($entity['autorizacion']);
		$factura->setObservacion($entity['observacion']);
		$factura->setProfesional($entity['profesional']);
		
    	  
    	$em->persist($factura);
    	$em->flush();
    		 
    	$this->get('session')->setFlash('ok', 'La factura ha sido registrada éxitosamente.');
    			 
    	return $this->redirect($this->generateUrl('factura_edit', array("factura" => $factura->getId())));
    		
    }
    
    public function editActividadAction($factura)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
    
    	if (!$factura) {
    		throw $this->createNotFoundException('La factura solicitada no existe');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_actividad_new"));

    	$form = $this->createForm(new FacturaType(), $factura);
    
    	return $this->render('FacturacionBundle:Factura:edit.html.twig', array(
    			'factura' => $factura,
    			'form' => $form->createView()
    	));
    }
    
    /**
     * @uses Función que consulta la información del paciente por tipo y número de identificación.
     *
     * @param ninguno
     */
    public function ajaxBuscarPacienteAction() {
    
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
    					"rango" => $paciente->getRango(),
    					"afiliacion" => $paciente->getTipoAfi(),
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
}