<?php
namespace knx\HistoriaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use knx\HistoriaBundle\Entity\Cie;
use knx\HistoriaBundle\Form\SearchType;

class CieController extends Controller {
	public function listAction() {
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs
				->addItem("Inicio",
						$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs
				->addItem("Cie", $this->get("router")->generate("cie_list"));
		$breadcrumbs->addItem("Listado");

		$em = $this->getDoctrine()->getEntityManager();
		$form = $this->createForm(new SearchType());
		$Cie = $em->getRepository('HistoriaBundle:Cie')
				->findBy(array(), array('nombre' => 'ASC'));

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($Cie, $this->getRequest()->query->get('page', 1), 10);

		return $this
				->render('HistoriaBundle:Cie:list.html.twig',
						array('cies' => $pagination, 'filtro' => 1,
								'search_form' => $form->createView(),));
	}

	public function searchAction() {
		$request = $this->getRequest();
		$form = $this->createForm(new SearchType());
		$form->bindRequest($request);
		$Cie = null;

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Cie", $this->get("router")->generate("cie_list"));
		$breadcrumbs->addItem("Listado");

		if ($form->isValid()) {
			$nombre = $form->get('nombre')->getData();
			$type = $form->get('typeChoice')->getData();
			$em = $this->getDoctrine()->getEntityManager();

			if($type == 'name')
			{
				$dql = $em->createQuery(
						"SELECT c FROM HistoriaBundle:Cie c WHERE c.nombre LIKE :nombre");
				$dql->setParameter('nombre', $nombre . '%');
				$Cie = $dql->getResult();
			}else{
				$dql = $em->createQuery(
						"SELECT c FROM HistoriaBundle:Cie c WHERE c.codigo LIKE :cod");
				$dql->setParameter('cod', $nombre . '%');
				$Cie = $dql->getResult();
			}

			if (!$Cie) {
				$this->get('session')->setFlash('error','Busquedad no exítosa, vuelva a intentar.');
				return $this->redirect($this->generateUrl('cie_list'));
			}
		}

		return $this->render('HistoriaBundle:Cie:list.html.twig',array(
								'cies' => $Cie,
								'filtro' => 0,
								'search_form' => $form->createView(),
				));
	}
}
