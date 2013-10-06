<?php

namespace knx\UsuarioBundle\Component\Security\Http\Authentication;
 
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	protected $router;
	protected $security;
	
	public function __construct(Router $router, SecurityContext $security)
	{
		$this->router = $router;
		$this->security = $security;
	}	
	
	/**
	 * @param Request $request
	 * @param TokenInterface $token
	 */
	public function onAuthenticationSuccess(Request $request, TokenInterface $token) 
	{
		if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
			
			$response = new RedirectResponse($this->router->generate('parametrizar_index'));
		
		}elseif ($this->security->isGranted('ROLE_MEDICO')){
			
			$response = new RedirectResponse($this->router->generate('historia_index'));
		
		}
		
		return $response;

	}
}
