<?php

namespace knx\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\UsuarioBundle\Entity\Usuario;
use knx\UsuarioBundle\Form\UsuarioBasicType;
use knx\UsuarioBundle\Form\UsuarioType;
use Symfony\Component\HttpFoundation\Response;


class UsuarioController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Usuario", $this->get("router")->generate("usuario_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $usuario = $em->getRepository('UsuarioBundle:Usuario')->findAll();
        
        return $this->render('UsuarioBundle:Usuario:list.html.twig', array(
                'usuarios'  => $usuario
        ));
    }
    
    /**
     * @uses Función que consulta los por un cargo dado.
     *
     * @param ninguno
     */
    public function ajaxBuscarUsuarioPorCargoAction()
    {
    
    	$request = $this->get('request');
    	 
    	$cargo = $request->request->get('cargo');
    
    	if(trim($cargo)){

    		$em = $this->getDoctrine()->getEntityManager();
    		$usuarios = $em->getRepository('UsuarioBundle:Usuario')->findBy(array('cargo' => $cargo, 'enabled' => 1));
    		 
    		if($usuarios){
    			$response=array("responseCode" => 200);
    
    			foreach ($usuarios as $key => $value){
    				$response['usuarios'][$value->getId()] = $value->getNombre()." ".$value->getApellido();
    			}
    		}
    		else{
    			$response=array("responseCode"=>400, "msg"=>"No hay usuarios disponibles para el cargo dado.");
    		}
    	}else{
    		$response=array("responseCode"=>400, "msg"=>"El cargo no es valido.");
    	}
    	 
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
}