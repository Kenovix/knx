<?php

namespace knx\UsuarioBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;


class ProfileController extends BaseController
{
	
	/**
	 * Show the user
	 */
	public function showAction()
	{
		$breadcrumbs = $this->container->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->container->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Usuarios", $this->container->get("router")->generate("usuario_list"));
		$breadcrumbs->addItem("Crear");
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
	
		return $this->container->get('templating')->renderResponse('UsuarioBundle:Profile:show.html.twig', array('user' => $user));
	}
	
	/**
	 * Edit the user
	 */
	public function editAction()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
	
		$form = $this->container->get('fos_user.profile.form');
		$formHandler = $this->container->get('fos_user.profile.form.handler');
	
		$process = $formHandler->process($user);
		if ($process) {
			$this->setFlash('fos_user_success', 'profile.flash.updated');
	
			return new RedirectResponse($this->getRedirectionUrl($user));
		}
	
		return $this->container->get('templating')->renderResponse(
				'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
				array('form' => $form->createView())
		);
	}
	
	/**
	 * Generate the redirection url when editing is completed.
	 *
	 * @param \FOS\UserBundle\Model\UserInterface $user
	 *
	 * @return string
	 */
	protected function getRedirectionUrl(UserInterface $user)
	{
		return $this->container->get('router')->generate('fos_user_profile_show');
	}
	
	/**
	 * @param string $action
	 * @param string $value
	 */
	protected function setFlash($action, $value)
	{
		$this->container->get('session')->getFlashBag()->set($action, $value);
	}
}