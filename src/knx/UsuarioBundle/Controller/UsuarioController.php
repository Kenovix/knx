<?php

namespace knx\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\UsuarioBundle\Entity\Usuario;
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
    
    
    public function showAction($usuario)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$usuario = $em->getRepository('UsuarioBundle:Usuario')->find($usuario);
    
    	if (!$usuario) {
    		throw $this->createNotFoundException('El usuario solicitado no esta disponible.');
    	}   	
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Usuario", $this->get("router")->generate("usuario_list"));
    	$breadcrumbs->addItem($usuario->getUsername());
    
    	return $this->render('UsuarioBundle:Usuario:show.html.twig', array(
    			'usuario'  => $usuario
    	));
    }
    
    
    /**
     * Edit the user
     */
    public function editAction($usuario)
    {
    	$userManager = $this->container->get('fos_user.user_manager');
		$usuario = $userManager->findUserByUsername($usuario);
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Usuario", $this->get("router")->generate("usuario_list"));
    	$breadcrumbs->addItem("Editar ".$usuario->getUsername());
    	    			
		$form = $this->createForm(new UsuarioType('knx\UsuarioBundle\Entity\Usuario'), $usuario);
		
		return $this->render('UsuarioBundle:Usuario:edit.html.twig', array(
				'usuario'  => $usuario,
				'form' => $form->createView()
		));
    }

    /**
     * Update the user
     */
    public function updateAction($usuario)
    {
    	$userManager = $this->container->get('fos_user.user_manager');
    	$usuario = $userManager->findUserBy(array('id' => $usuario));
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Usuario", $this->get("router")->generate("usuario_list"));
    	$breadcrumbs->addItem("Editar ".$usuario->getUsername());
    
    	$form = $this->createForm(new UsuarioType('knx\UsuarioBundle\Entity\Usuario'), $usuario);
    	
    	$request = $this->getRequest();
    
    	if ($request->getMethod() == 'POST') {
    	
    		$form->bind($request);
    	
    		if ($form->isValid()) {

    	 		$userManager->updateUser($usuario);
    
    			return $this->redirect($this->generateUrl('usuario_edit',
    								array('usuario' => $usuario)));
    		}
    	}
    
    	return $this->render('UsuarioBundle:Usuario:edit.html.twig', array(
    			'usuario'  => $usuario,
    			'form' => $form->createView()
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