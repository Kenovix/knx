<?php

namespace knx\HistoriaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use knx\HistoriaBundle\Entity\Medicamento;
use knx\HistoriaBundle\Form\MedicamentoType;
use knx\HistoriaBundle\Form\SearchType;

class MedicamentoController extends Controller 
{
	public function newAction() 
	{
		$entity = new Medicamento();
		$form = $this->createForm(new MedicamentoType(), $entity);

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Medicamento",$this->get("router")->generate("medicamento_list"));
		$breadcrumbs->addItem("Nuevo");

		return $this->render('HistoriaBundle:Medicamento:new.html.twig',array('form' => $form->createView()));
	}

	public function saveAction() 
	{
		$medicamento = new Medicamento();

		$request = $this->getRequest();
		$form = $this->createForm(new MedicamentoType(), $medicamento);
		$form->bindRequest($request);

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Medicamento",$this->get("router")->generate("medicamento_list"));
		$breadcrumbs->addItem("Nuevo");

		if ($form->isValid()) {

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($medicamento);
			$em->flush();

			$this->get('session')->setFlash('ok','El Medicamento ha sido creada éxitosamente.');
			return $this->redirect($this->generateUrl('medicamento_show',array("medicamento" => $medicamento->getId())));
		} else {
			$this->get('session')->setFlash('error','El formulario no es valido! verifique la información.');

			return $this->render('HistoriaBundle:Medicamento:new.html.twig',array('form' => $form->createView()));
		}
	}

	public function showAction($medicamento) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$medicamento = $em->getRepository('HistoriaBundle:Medicamento')->find($medicamento);

		if (!$medicamento) {
			throw $this->createNotFoundException('El medicamento solicitada no esta disponible.');
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Medicamento",$this->get("router")->generate("medicamento_list"));
		$breadcrumbs->addItem("Detalle de ". $medicamento->getPrincipioActivo());

		return $this->render('HistoriaBundle:Medicamento:show.html.twig',array('medicamento' => $medicamento));
	}

	public function editAction($medicamento) 
	{
		$em = $this->getDoctrine()->getEntityManager();
		$medicamento = $em->getRepository('HistoriaBundle:Medicamento')->find($medicamento);

		if (!$medicamento) {
			throw $this->createNotFoundException('El Medicamento solicitada no existe');
		}

		$editForm = $this->createForm(new MedicamentoType(), $medicamento);

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Medicamento",$this->get("router")->generate("medicamento_list"));
		$breadcrumbs->addItem("Detalle",$this->get("router")->generate("medicamento_show",array("medicamento" => $medicamento->getId())));
		$breadcrumbs->addItem("Modificar " . $medicamento->getPrincipioActivo());

		return $this->render('HistoriaBundle:Medicamento:edit.html.twig',array(
				'medicamento' => $medicamento,
				'edit_form' => $editForm->createView(),
			));
	}

	public function updateAction($medicamento) {
		$em = $this->getDoctrine()->getEntityManager();
		$medicamento = $em->getRepository('HistoriaBundle:Medicamento')->find($medicamento);

		if (!$medicamento) {
			throw $this->createNotFoundException('El Medicamento solicitada no existe');
		}

		$editForm = $this->createForm(new MedicamentoType(), $medicamento);
		$request = $this->getRequest();
		$editForm->bindRequest($request);

		if ($editForm->isValid()) {

			$em->persist($medicamento);
			$em->flush();

			$this->get('session')->setFlash('ok','El Medicamento ha sido modificada éxitosamente.');
			return $this->redirect($this->generateUrl('medicamento_edit',array('medicamento' => $medicamento->getId())));
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Medicamento",$this->get("router")->generate("medicamento_list"));
		$breadcrumbs->addItem("Detalle",$this->get("router")->generate("medicamento_show",array("medicamento" => $medicamento->getId())));
		$breadcrumbs->addItem("Modificar " . $medicamento->getPrincipioActivo());

		return $this->render('HistoriaBundle:Medicamento:edit.html.twig',array(
				'medicamento' => $medicamento,
				'edit_form' => $editForm->createView(),
			));
	}

	public function listAction() {
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Medicamento",$this->get("router")->generate("medicamento_list"));
		$breadcrumbs->addItem("Listado");

		$em = $this->getDoctrine()->getEntityManager();
		$form = $this->createForm(new SearchType());
		$medicamento = $em->getRepository('HistoriaBundle:Medicamento')->findBy(array(), array('principioActivo' => 'ASC'));

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($medicamento,$this->getRequest()->query->get('page', 1), 10);

		return $this->render('HistoriaBundle:Medicamento:list.html.twig',array(
				'medicamentos' => $pagination,
				'filtro' => 1,
				'search_form' => $form->createView(),
				));
	}

	public function searchAction() {
		$request = $this->getRequest();
		$form = $this->createForm(new SearchType());
		$form->bindRequest($request);
		$medicamento = null;

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Medicamento",$this->get("router")->generate("medicamento_list"));
		$breadcrumbs->addItem("Listado");

		if ($form->isValid()) {
			$nombre = $form->get('nombre')->getData();
			$em = $this->getDoctrine()->getEntityManager();

			$dql = $em->createQuery("SELECT m FROM HistoriaBundle:Medicamento m WHERE m.principioActivo LIKE :nombre");
			$dql->setParameter('nombre', $nombre . '%');
			$medicamento = $dql->getResult();

			if (!$medicamento) {
				$this->get('session')->setFlash('error','Busquedad no exítosa, vuelva a intentar.');
				return $this->redirect($this->generateUrl('medicamento_list'));
			}
		}

		return $this->render('HistoriaBundle:Medicamento:list.html.twig',array(
						'medicamentos' => $medicamento,
						'filtro' => 0,
						'search_form' => $form->createView(),
					));
	}
}
