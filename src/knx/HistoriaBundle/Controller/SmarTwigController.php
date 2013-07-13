<?php

namespace knx\HistoriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use knx\HistoriaBundle\Entity\Notas;
use knx\HistoriaBundle\Form\NotasType;

class SmarTwigController extends Controller
{
	public function saveSignosAction($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
		
		if (!$factura) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}	
		
		$historia = $factura->getHc();
	
		if (!$historia) {
			/* Si la historia no existe se procedera a crear una historia en code behind, despues de crear la
			 * historia se procede a visualizar el formulario de las notas.
			 */
			$historia = $em->getRepository('HistoriaBundle:Notas')->createEmptyHc($factura);
		}
		
		$notas = new Notas();
	
		$request = $this->getRequest();
		$notas->setFecha(new \DateTime('now'));
		
		$form_nota = $this->createForm(new NotasType(), $notas);
		$form_nota->bindRequest($request);
	
		if($form_nota->isValid()){		
	
			//$usuario = $this->get('security.context')->getToken()->getUser();
			$usuario = $em->getRepository('UsuarioBundle:Usuario')->find(6);
	
			$notas->setHc($historia);
			$notas->setResponsable($usuario);
			$em->persist($notas);
			$em->flush();	
				
			$message = "La informacion de la nota ha sido creada éxitosamente. Ahora Puede cerrar la ventana.";			
			return new Response($message, 200,array('Content-Type' => 'text/plain'));
	
		}else{
			$message = "El formulario no es valido! verifique que la informacion sea correcta, todos los campos son numericos"; 
			return new Response($message, 200,array('Content-Type' => 'text/plain'));
		}		
	}
}
