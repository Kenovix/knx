<?php

namespace knx\HistoriaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use knx\HistoriaBundle\Entity\Hc;
use knx\HistoriaBundle\Form\OdontologiaType;
use knx\HistoriaBundle\Entity\Notas;


class OdontologiaController extends Controller
{
	// Informacion a procesar para las consultas de odontologia
	public function editOdontologiaAction($factura)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
		$historia = $factura->getHc();
	
		$usuario = $this->get('security.context')->getToken()->getUser();
		$perfil = null;
		foreach ($usuario->getRoles() as $role)
		{
			if($role == 'ROLE_MEDICO' || $role == 'ROLE_ODONTOLOGO')
			{
				$perfil = $role;
			}
		}
	
		if(!$perfil)
			return $this->redirect($this->generateUrl('historia_externas_list'));
	
		/* No se verifica la existencia del paciente y los servicios porque si existe la factura existe el paciente
		 * y si existe la historia existen los servicios.
		*/
		if (!$factura || !$historia) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}
	
		// se filtra la historia para validar si se puede dar permisos de edition o no
		$facturaCargo = $em->getRepository('HistoriaBundle:Hc')->closeFacturaCargoHc($factura->getId(),'CE');
		if($facturaCargo->getEstado() == "C")
		{
			$paciente = $factura->getPaciente();
			return $this->redirect($this->generateUrl('historia_search_result', array('paciente' => $paciente->getId())));
		}				
	
		// se cargan los respectivos objetos para que el formulario los visualice correctamente.
		$historia->setFechaEgre(new \DateTime('now'));
		$form_historia = $this->createForm(new OdontologiaType(), $historia);
	
		return $this->validaOdontologia($factura, $historia, $form_historia);		
	}
	
	public function updateAction($factura) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$factura = $em->getRepository('FacturacionBundle:Factura')->find($factura);
		$historia = $factura->getHc();	

		if (!$factura || !$historia) {
			throw $this->createNotFoundException('La informacion solicitada no esta disponible.');
		}
		
				
		$historia->setFechaEgre(new \DateTime('now'));
		$form_historia = $this->createForm(new OdontologiaType(), $historia);
		$request = $this->getRequest();
		$form_historia->bindRequest($request);
				
		
		if($form_historia->isValid()) 
		{			
			/* Para el ingreso de los sevicios se trabaja con los IDs mas no con sus objetos ya q la informacion
			 * que se almacena en la historia no son relaciones, pero el formulario si trabaja con objetos.
			 */ 
			
			$historia->setServiEgre($factura->getServicio()->getId());						
			$facturaCargo = $em->getRepository('HistoriaBundle:Hc')->closeFacturaCargoHc($factura->getId(),'CE');							
			$facturaCargo->setEstado('C');	
			$em->persist($facturaCargo);
			$factura->setEstado('C');
		        $em->persist($factura);

			$historia->setFactura($factura);
			$historia->setDestino('1');
                        $historia->setEstado('CO');

			$em->persist($historia);		
			$em->flush();		

			$this->get('session')->setFlash('ok','La historia clinica ha sido modificada éxitosamente.');
			return $this->redirect($this->generateUrl('odontologia_edit',array('factura' => $factura->getId())));
		}else{
			return $this->validaOdontologia($factura, $historia, $form_historia);			
		}		
	}
	
	
	public function validaOdontologia($factura, $historia, $form_historia)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		$usuario = $this->get('security.context')->getToken()->getUser();
		$perfil = null;
		foreach ($usuario->getRoles() as $role)
		{
			if($role == 'ROLE_MEDICO' || $role == 'ROLE_ODONTOLOGO')
			{
				$perfil = $role;
			}
		}
		
		/* Se consultan los respectivos objetos q se trabajan en la historia todo por medio de la relacion
		 * OneToOne que tiene la hisotia y la factura.		*/
		$paciente = $factura->getPaciente();
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));
		
		// consulto la afiliacion ya que esta contiene el nivel y el rango del paciente con su cliente
		$cliente = $factura->getCliente();
		$afiliacion = $em->getRepository('ParametrizarBundle:Afiliacion')->findOneBy(array('cliente' => $cliente->getId(), 'paciente' => $paciente->getId()));
		
		// Se realizan las respectivas consultas a sus respectivos repositorios para traer
		// la informacion correspondiente que se visualizara en la historia
		$hc_cie = $em->getRepository('HistoriaBundle:Hc')->findHcDx($historia->getId());
		
		// rastro de miga
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Odontologia",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Modificar " . $paciente->getPriNombre());
		
		$this->get('session')->setFlash('info','Los campos * son obligatorios, valide que su información sea correcta para poder guardar.');
		
		// Visualizacion de la plantilla.
		return $this->render('HistoriaBundle:Odontologia:Odontologia.html.twig',array(
				'factura'  	 => $factura,
				'afiliacion' => $afiliacion,
				'today'		 => new \DateTime('now'),  // fecha para el ingreso en algunos campos del formulario
				'paciente' 	 => $paciente,
				'usuario'  	 => $usuario,
				'historia' 	 => $historia,
				'hc_cie' 	 => $hc_cie,
				'edit_form'  => $form_historia->createView(),
		));
	}
}
