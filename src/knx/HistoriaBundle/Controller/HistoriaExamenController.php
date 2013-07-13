<?php

namespace knx\HistoriaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use knx\HistoriaBundle\Entity\HcExamen;

class HistoriaExamenController extends Controller {
	public function examenHcSaveAction() 
	{
		$request = $this->get('request');

		$hc = $request->request->get('hcId');
		$exa = $request->request->get('exaId');

		$em = $this->getDoctrine()->getEntityManager();

		// se verifica que la historia y el examen existan, si no se genera una exception.
		$historia = $em->getRepository('HistoriaBundle:Hc')->find($hc);
		$examen = $em->getRepository('HistoriaBundle:Examen')->find($exa);

		if (!$historia || !$examen) {
			throw $this->createNotFoundException('informacion solicitada no existe');
		}

		// Se consulta que la historia tenga el examen relacionado si es asi se genera un mensaje
		// que el examen ya esta asignado si no se guarda dicha relacion.

		$hc_exa = $em->getRepository('HistoriaBundle:HcExamen')->find(array('hc' => $hc, 'examen' => $exa));

		if (!$hc_exa) {
			$hc_exa = new HcExamen();
			$hc_exa->setExamen($examen);
			$hc_exa->setHc($historia);
			$hc_exa->setFecha(new \DateTime('now'));
			$hc_exa->setEstado('P');

			$em->persist($hc_exa);
			$em->flush();

			$response = array("responseCode" => 200,"msg" => "Diagnostico solicitado correctamente");

			$response['examen']['id'] = $examen->getId();
			$response['examen']['nombre'] = $examen->getNombre();
			$response['examen']['codigo'] = $examen->getCodigo();
			$response['examen']['tipo'] = $examen->getTipo();			
			
		} else {
			$response = array("responseCode" => 400,"msg" => "El Examen seleccionado ya esta asignado en la historia clinica.");
		}

		$return = json_encode($response);
		return new Response($return, 200,array('Content-Type' => 'application/json'));
	}

	public function examenDelHcAction() {
		$reques = $this->get('request');

		$exa = $reques->request->get('exaId');
		$hc = $reques->request->get('hc');

		$em = $this->getDoctrine()->getEntityManager();
		$historia = $em->getRepository('HistoriaBundle:Hc')->find($hc);
		$examen = $em->getRepository('HistoriaBundle:Examen')->find($exa);

		if (!$historia || !$examen) {
			$response = array("responseCode" => 400,"msg" => "Examen solicitado incorrecto");
		} else {

			$hc_exa = $em->getRepository('HistoriaBundle:HcExamen')->find(array('hc' => $hc, 'examen' => $exa));

			$em->remove($hc_exa);
			$em->flush();

			$response = array("responseCode" => 200,"msg" => "Examen eliminado éxitosamente");
		}
		$return = json_encode($response);
		return new Response($return, 200,array('Content-Type' => 'application/json'));
	}

	public function resultadoSaveExamenAction()
	{
		$request = $this->get('request');
		
		$exa = $request->request->get('examenId');
		$hc = $request->request->get('hcId');
		$resultado = $request->request->get('result');
		
		$em = $this->getDoctrine()->getEntityManager();
		$historia = $em->getRepository('HistoriaBundle:Hc')->find($hc);
		$examen = $em->getRepository('HistoriaBundle:Examen')->find($exa);
		
		if (!$historia || !$examen) {
			$response = array("responseCode" => 400,"msg" => "Examen solicitado incorrecto");
		} else {
		
			$hc_exa = $em->getRepository('HistoriaBundle:HcExamen')->find(array('hc' => $hc, 'examen' => $exa));
		
			$hc_exa->setResultado($resultado);
			$hc_exa->setEstado('I');
			$em->persist($hc_exa);
			$em->flush();
		
			$response = array("responseCode" => 200,"msg" => "Resultado del Examen almacenado éxitosamente");
		}
		$return = json_encode($response);
		return new Response($return, 200,array('Content-Type' => 'application/json'));
	}
}
