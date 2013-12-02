<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use knx\ParametrizarBundle\Entity\Cliente;
use knx\ParametrizarBundle\Entity\Paciente;
use knx\ParametrizarBundle\Entity\Afiliacion;
use knx\ParametrizarBundle\Form\AfiliacionType;

class AfiliacionController extends Controller
{
    
    public function saveAction($paciente)
    {
        $em = $this->getDoctrine()->getEntityManager();        
        $paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);
        
        if (!$paciente) {
            throw $this->createNotFoundException('El paciente solicitado no existe.');
        }
        
        $entity  = new Afiliacion();
        $request = $this->getRequest();
        $form    = $this->createForm(new AfiliacionType(), $entity);
        $registro = $request->get($form->getName());        
        $form->bindRequest($request);
               
    
        if ($registro['tipoRegist'] && $form->get('cliente')->getData()) {

            $afiliacion = $em->getRepository('ParametrizarBundle:Afiliacion')->findBy(array('cliente' => $entity->getCliente()->getId(), 'paciente' => $paciente->getId()));
            
            if($afiliacion){
                $this->get('session')->setFlash('error', 'La asociación ya existe.');
                
                return $this->redirect($this->generateUrl('paciente_show', array("paciente" => $paciente->getId())));
            }
            
            $entity->setPaciente($paciente);
            $entity->setTipoRegist($registro['tipoRegist']);
            $em->persist($entity);
            $em->flush();
    
            $this->get('session')->setFlash('info', 'La asociación ha sido registrada éxitosamente.');    
    
            return $this->redirect($this->generateUrl('paciente_show', array("paciente" => $paciente->getId())));    
        }
        // se intancian se consultas los objetos q se van a visualizar en la plantilla
        $afiliaciones = $em->getRepository('ParametrizarBundle:Afiliacion')->findByPaciente($paciente);
        
        $depto = $em->getRepository('ParametrizarBundle:Depto')->find($paciente->getDepto());
        $mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($paciente->getMupio());
        $paciente->setDepto($depto);
        $paciente->setMupio($mupio);
        
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
        $breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
        $breadcrumbs->addItem("Detalles", $this->get("router")->generate("paciente_show", array("paciente" => $paciente->getId() )));
    
        return $this->render('ParametrizarBundle:Paciente:show.html.twig', array(
                'paciente' => $paciente,
        		'afiliaciones' => $afiliaciones,
                'form'   => $form->createView()
        ));    
    }
    
    public function jxsaveAction($paciente)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);
    
    	if (!$paciente) {
    		throw $this->createNotFoundException('El paciente solicitado no existe.');
    	}
    
    	$entity  = new Afiliacion();
    	$request = $this->getRequest();
    	$form    = $this->createForm(new AfiliacionType(), $entity);
    	$registro = $request->get($form->getName());
    	$form->bindRequest($request);
    	 
    
    	if ($registro['tipoRegist'] && $form->get('cliente')->getData()) {
    
    		$afiliacion = $em->getRepository('ParametrizarBundle:Afiliacion')->findBy(array('cliente' => $entity->getCliente()->getId(), 'paciente' => $paciente->getId()));
    
    		if($afiliacion){
    			$this->get('session')->setFlash('error', 'La asociación ya existe.');
    
    			return $this->redirect($this->generateUrl('paciente_jx_show', array("paciente" => $paciente->getId())));
    		}
    
    		$entity->setPaciente($paciente);
    		$entity->setTipoRegist($registro['tipoRegist']);
    		$em->persist($entity);
    		$em->flush();
    
    		$this->get('session')->setFlash('info', 'La asociación ha sido registrada éxitosamente.');
    
    		return $this->redirect($this->generateUrl('paciente_jx_show', array("paciente" => $paciente->getId())));
    	}
    	// se intancian se consultas los objetos q se van a visualizar en la plantilla
    	$afiliaciones = $em->getRepository('ParametrizarBundle:Afiliacion')->findByPaciente($paciente);
    
    	$depto = $em->getRepository('ParametrizarBundle:Depto')->find($paciente->getDepto());
    	$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($paciente->getMupio());
    	$paciente->setDepto($depto);
    	$paciente->setMupio($mupio);
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
    	$breadcrumbs->addItem("Detalles", $this->get("router")->generate("paciente_show", array("paciente" => $paciente->getId() )));
    
    	return $this->render('ParametrizarBundle:Paciente:jx_show.html.twig', array(
    			'paciente' => $paciente,
    			'afiliaciones' => $afiliaciones,
    			'form'   => $form->createView()
    	));
    }
    
    
    public function deleteAction($paciente, $cliente)
    {        
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('ParametrizarBundle:Afiliacion')->find(array('cliente' => $cliente, 'paciente' => $paciente));
    
            if (!$entity) {
                throw $this->createNotFoundException('La asociación a eliminar no existe.');
            }
    
            $em->remove($entity);
            $em->flush();

            $this->get('session')->setFlash('info', 'La asociación ha sido eliminada éxitosamente.');
    
            return $this->redirect($this->generateUrl('paciente_show', array("paciente" => $paciente)));
    }
    
    public function jxdeleteAction($paciente, $cliente)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$entity = $em->getRepository('ParametrizarBundle:Afiliacion')->find(array('cliente' => $cliente, 'paciente' => $paciente));
    
    	if (!$entity) {
    		throw $this->createNotFoundException('La asociación a eliminar no existe.');
    	}
    
    	$em->remove($entity);
    	$em->flush();
    
    	$this->get('session')->setFlash('info', 'La asociación ha sido eliminada éxitosamente.');
    
    	return $this->redirect($this->generateUrl('paciente_jx_show', array("paciente" => $paciente)));
    }
    
    
    public function ajaxSaveAction()
    {
    	$request = $this->get('request');
    	
    	$paciente = $request->request->get('paciente');
    	$cliente = $request->request->get('cliente');
    	$tipoRegist = $request->request->get('tipoRegist');
    	$rango = $request->request->get('rango');

    	if($paciente && $cliente){
    		$em = $this->getDoctrine()->getEntityManager();
    		    		    		
    		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->findOneBy(array('identificacion' => $paciente));
    		$cliente = $em->getRepository('ParametrizarBundle:Cliente')->find($cliente);

    		if($paciente && $cliente){
    			
    			$afiliacion = $em->getRepository('ParametrizarBundle:Afiliacion')->findBy(array('cliente' => $cliente->getId(), 'paciente' => $paciente->getId()));

    			if($afiliacion){
    				$response=array("responseCode"=>400, "msg"=>"El cliente ya se encuentra asociado al paciente.");
    			}else{
    				$entity  = new Afiliacion();
    				 
    				$entity->setPaciente($paciente);
    				$entity->setCliente($cliente);
    				$entity->setTipoRegist($tipoRegist);
    				$entity->setRango($rango);
    				$em->persist($entity);
    				$em->flush();
    				 
    				$response=array("responseCode"=>200);
    				 
    				$response['id']= $entity->getCliente()->getId();
    				$response['nombre']= $entity->getCliente()->getNombre();
    			}
    		}else{
    			$response=array("responseCode"=>400);
    		}
    	}
    	
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }

    public function tipoClienteAction()
    {
    	$request = $this->get('request');
    	$cliente = $request->request->get('cliente');
    	
    	if($cliente){
    		$em = $this->getDoctrine()->getEntityManager();
    		$cliente = $em->getRepository('ParametrizarBundle:Cliente')->find($cliente);    		
    		
    		if($cliente){
    			$response=array("responseCode"=>200);    				
    			$response['id']= $cliente->getId();
    			$response['regimen']= $cliente->getTipo();
    		}else{
    			$response=array("responseCode"=>400, "msg"=>"El cliente no existe.");
    		}
    	}else{
    			$response=array("responseCode"=>400, "msg"=>"El cliente no existe.");
    		}
    		
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
}