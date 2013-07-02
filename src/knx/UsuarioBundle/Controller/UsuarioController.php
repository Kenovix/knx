<?php

namespace knx\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\UsuarioBundle\Entity\Usuario;
use knx\UsuarioBundle\Form\UsuarioBasicType;


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
    
	public function editAction($usuario)
    {
		$userManager = $this->container->get('fos_user.user_manager');
		$usuario = $userManager->findUserBy(array('id' => $usuario));
		
		$form = $this->createForm(new UsuarioBasicType(), $usuario);
		
		//$form = $this->container->get('fos_user.registration.form');
		
		return $this->render('UsuarioBundle:Usuario:edit.html.twig', array(
				'usuario'  => $usuario,
				'form' => $form->createView()
		));
		
    }
    
    public function updateAction($usuario)
    {
    	$userManager = $this->container->get('fos_user.user_manager');
    	$usuario = $userManager->findUserBy(array('id' => $usuario));
    
    	$editForm = $this->createForm(new UsuarioType(), $usuario);
    
    	if ($editForm->isValid()) {
    		$userManager->updateUser($usuario);
    
    		return $this->redirect($this->generateUrl('usuario_edit',
    				array('usuario' => $usuario->getId())));
    	}
    	
    	return $this->render('UsuarioBundle:Usuario:list.html.twig', array(
    			'usuario'  => $usuario,
				'form' => $editForm->createView()
    	));
    }
}