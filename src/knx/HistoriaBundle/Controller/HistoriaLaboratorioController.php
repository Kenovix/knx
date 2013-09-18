<?php

namespace knx\HistoriaBundle\Controller;
use knx\HistoriaBundle\Entity\MedicamentoHistoria;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class HistoriaLaboratorioController extends Controller {
	public function laboratorioHcSaveAction() {
		$request = $this->get('request');

		$hc = $request->request->get('hcId');
		$lab = $request->request->get('labId');

		$em = $this->getDoctrine()->getEntityManager();

		// se verifica que la historia y el examen existan, si no se genera una exception.
		$historia = $em->getRepository('HistoriaBundle:Hc')->find($hc);
		$laboratorio = $em->getRepository('HistoriaBundle:Medicamento')
				->find($lab);

		if (!$historia || !$laboratorio) {
			throw $this
					->createNotFoundException(
							'informacion solicitada no existe');
		}

		// Se consulta que la historia tenga el examen relacionado si es asi se genera un mensaje
		// que el examen ya esta asignado si no se guarda dicha relacion.

		$hc_lab = $em->getRepository('HistoriaBundle:MedicamentoHistoria')
				->find(array('hc' => $hc, 'medicamento' => $lab));

		if (!$hc_lab) {
			$hc_lab = new MedicamentoHistoria();
			$hc_lab->setMedicamento($laboratorio);
			$hc_lab->setHc($historia);
			$hc_lab->setEstado($laboratorio->getEstado());

			$em->persist($hc_lab);
			$em->flush();

			$response = array("responseCode" => 200,
					"msg" => "Diagnostico solicitado correctamente");

			$response['medicamento']['id'] = $laboratorio->getId();
			$response['medicamento']['principioActivo'] = $laboratorio
					->getPrincipioActivo();
			$response['medicamento']['presentacion'] = $laboratorio
					->getPresentacion();
			$response['medicamento']['concentracion'] = $laboratorio
					->getConcentracion();
			$response['medicamento']['posologia'] = $laboratorio
					->getPosologia();
		} else {
			$response = array("responseCode" => 400,
					"msg" => "El medicamento seleccionado ya esta asignado en la historia clinica.");
		}

		$return = json_encode($response);
		return new Response($return, 200,
				array('Content-Type' => 'application/json'));
	}

	public function laboratorioDelHcAction() {
		$reques = $this->get('request');

		$hc = $reques->request->get('hc');
		$lab = $reques->request->get('laboId');

		$em = $this->getDoctrine()->getEntityManager();
		$historia = $em->getRepository('HistoriaBundle:Hc')->find($hc);
		$laboratorio = $em->getRepository('HistoriaBundle:Medicamento')
				->find($lab);

		if (!$historia || !$laboratorio) {
			$response = array("responseCode" => 400,
					"msg" => "Medicamento solicitado incorrecto");
		} else {

			$hc_lab = $em->getRepository('HistoriaBundle:MedicamentoHistoria')
					->find(array('hc' => $hc, 'medicamento' => $lab));

			$em->remove($hc_lab);
			$em->flush();

			$response = array("responseCode" => 200,
					"msg" => "Examen eliminado éxitosamente");
		}
		$return = json_encode($response);
		return new Response($return, 200,
				array('Content-Type' => 'application/json'));
	}
}
