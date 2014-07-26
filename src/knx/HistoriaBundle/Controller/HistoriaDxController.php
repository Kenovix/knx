<?php
namespace knx\HistoriaBundle\Controller;
use knx\HistoriaBundle\Entity\HcDx;

use Symfony\Component\BrowserKit\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class HistoriaDxController extends Controller {

	public function saveHcDxAction() {
		$request = $this->get('request');

		$hc = $request->request->get('hcId');
		$cie = $request->request->get('cieId');

		$em = $this->getDoctrine()->getEntityManager();

		// se verifica que la historia y el diagnostico existan, si no se genera una exception.
		$historia = $em->getRepository('HistoriaBundle:Hc')->find($hc);
		$diagnostico = $em->getRepository('HistoriaBundle:Cie')->find($cie);

		if (!$historia || !$diagnostico) {
			throw $this->createNotFoundException('informacion solicitada no existe');
		}

		// Se consulta que la historia tenga el diagnostico relacionado si es asi se genera un mensaje
		// que el diagnostico ya esta asignado si no se guarda dicha relacion.

		$hc_cie = $em->getRepository('HistoriaBundle:HcDx')->find(array('hc' => $hc, 'cie' => $cie));

		if (!$hc_cie) {
			$hc_cie = new HcDx();
			$hc_cie->setCie($diagnostico);
			$hc_cie->setHc($historia);

			$em->persist($hc_cie);
			$em->flush();

			$response = array("responseCode" => 200,"msg" => "Diagnostico solicitado correctamente");

			$response['cie']['id'] = $diagnostico->getId();
			$response['cie']['nombre'] = $diagnostico->getNombre();
			$response['cie']['codigo'] = $diagnostico->getCodigo();
		} else {
			$response = array("responseCode" => 400,"msg" => "El Diagnostico seleccionado ya esta asignado en la historia clinica.");
		}

		$return = json_encode($response);
		return new Response($return, 200,array('Content-Type' => 'application/json'));
	}

	public function hcDxDeletedAction() {
		$reques = $this->get('request');

		$cie = $reques->request->get('cie');
		$hc = $reques->request->get('hc');

		$em = $this->getDoctrine()->getEntityManager();
		$historia = $em->getRepository('HistoriaBundle:Hc')->find($hc);
		$diagnostico = $em->getRepository('HistoriaBundle:Cie')->find($cie);

		if (!$historia || !$diagnostico) {
			$response = array("responseCode" => 400,"msg" => "Diagnostico solicitado incorrecto");
		} else {

			$hc_cie = $em->getRepository('HistoriaBundle:HcDx')->find(array('hc' => $hc, 'cie' => $cie));

			$em->remove($hc_cie);
			$em->flush();

			$response = array("responseCode" => 200,"msg" => "Diagnostico eliminado éxitosamente");
		}
		$return = json_encode($response);
		return new Response($return, 200,array('Content-Type' => 'application/json'));
	}
	
	
	
	// autocompletando para la busqueda de los diagnosticos en la historia
	public function codeAutocompleteAction()
	{			
		$response='';				
		if ( !isset($_REQUEST['term']) ){exit;}		
		
		$em = $this->getDoctrine()->getEntityManager();	
		$dql = $em->getRepository('HistoriaBundle:Cie')->findByCieCode($_REQUEST['term']);		
		
		if($dql){
			foreach ($dql as $data)
				$response[] = array(
						'label' => $data['codigo'].' - '.$data['nombre'],
						'value' => $data['codigo'],
						'id' 	=> $data['id'],
						'name'  => $data['nombre']
				);
		}
		
		$return = json_encode($response);
		return new Response($return, 200,array('Content-Type' => 'application/json'));
	}
	
	public function nameAutocompleteAction()
	{
		$response='';
		if ( !isset($_REQUEST['term']) ){exit;}
		
		$em = $this->getDoctrine()->getEntityManager();
		$dql = $em->getRepository('HistoriaBundle:Cie')->findByCieName($_REQUEST['term']);
		
		if($dql){
			foreach ($dql as $data)
				$response[] = array(
						'label' => $data['codigo'].' - '.$data['nombre'],
						'value' => $data['nombre'],
						'id' 	=> $data['id'],
						'code'  => $data['codigo']
				);
		}
		
		$return = json_encode($response);
		return new Response($return, 200,array('Content-Type' => 'application/json'));
	}
}
