<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FacturacionBundle\Entity\Factura;
use knx\FacturacionBundle\Form\FacturaType;
use Symfony\Component\HttpFoundation\Response;

class FacturaController extends Controller
{
    public function newActividadAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Nueva factura", $this->get("router")->generate("facturacion_actividad_new"));
    	
    	$factura = new Factura();
    	$form   = $this->createForm(new FacturaType(), $factura);

    	return $this->render('FacturacionBundle:Factura:new.html.twig', array(
    			'form'   => $form->createView()
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
    		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->findOneBy(array('tipoId' => $tipoid, 'identificacion' => $id));
    	
    		if($paciente){
    			$cliente = $em->getRepository('ParametrizarBundle:Afiliacion')->findBy(array('paciente' => $paciente->getId()));
    			 
    			$response=array("responseCode" => 200,
    					"id" => $paciente->getId(),
    					"nombre" => $paciente->getPriNombre()." ".$paciente->getSegNombre()." ".$paciente->getPriApellido()." ".$paciente->getSegApellido());
    	
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
