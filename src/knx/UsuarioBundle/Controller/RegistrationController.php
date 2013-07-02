<?php

namespace knx\UsuarioBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException; 

class RegistrationController extends BaseController
{
    public function registerAction()
    {    	
    	$breadcrumbs = $this->container->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->container->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Usuarios", $this->container->get("router")->generate("almacen_list"));
    	$breadcrumbs->addItem("Crear");
    	
    	$form = $this->container->get('fos_user.registration.form');
    	$formHandler = $this->container->get('fos_user.registration.form.handler');
    	$confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
    	
    	$process = $formHandler->process($confirmationEnabled);
    	if ($process) {
    		$user = $form->getData();
    	
    		$authUser = false;
    		if ($confirmationEnabled) {
    			$this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
    			$route = 'fos_user_registration_check_email';
    		} else {
    			$authUser = true;
    			$route = 'fos_user_registration_confirmed';
    		}
    	
    		$this->setFlash('fos_user_success', 'registration.flash.user_created');
    		$url = $this->container->get('router')->generate($route);
    		$response = new RedirectResponse($url);
    	
    		if ($authUser) {
    			//$this->authenticateUser($user, $response);
    		}
    	
    		return $response;
    	}
    	

    	return $this->container->get('templating')->renderResponse('UsuarioBundle:Registration:register.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
    
    public function confirmedAction()
    {
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	/*if (!is_object($user) || !$user instanceof UserInterface) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}*/
    
    	return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:confirmed.html.'.$this->getEngine(), array(
    			'user' => $user,
    	));
    }
}

