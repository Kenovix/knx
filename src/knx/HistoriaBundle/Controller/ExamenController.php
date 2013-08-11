<?php
namespace knx\HistoriaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use knx\HistoriaBundle\Entity\Examen;
use knx\HistoriaBundle\Form\ExamenType;
use knx\HistoriaBundle\Form\SearchType;

class ExamenController extends Controller
{
	public function newAction() 
	{
		$entity = new Examen();
		$form = $this->createForm(new ExamenType(), $entity);

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Examen",$this->get("router")->generate("examen_list"));
		$breadcrumbs->addItem("Nuevo");

		return $this->render('HistoriaBundle:Examen:new.html.twig',array(
				'form' => $form->createView()
				));
	}

	public function saveAction() {
		$examen = new Examen();

		$request = $this->getRequest();
		$form = $this->createForm(new ExamenType(), $examen);
		$form->bindRequest($request);

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Examen",$this->get("router")->generate("examen_list"));
		$breadcrumbs->addItem("Nuevo");

		if ($form->isValid()) {

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($examen);
			$em->flush();

			$this->get('session')->setFlash('ok', 'El examen  o el procedimiento ha sido creada éxitosamente.');
			return $this->redirect($this->generateUrl('examen_show',array("examen" => $examen->getId())));
		} else {
			$this->get('session')->setFlash('error','El formulario no es valido! verifique la información.');

			return $this->render('HistoriaBundle:Examen:new.html.twig',array('form' => $form->createView()));
		}
	}

	public function showAction($examen) {
		$em = $this->getDoctrine()->getEntityManager();
		$examen = $em->getRepository('HistoriaBundle:Examen')->find($examen);

		if (!$examen) {
			throw $this->createNotFoundException('El examen o procedimiento solicitada no esta disponible.');
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Examen",$this->get("router")->generate("examen_list"));
		$breadcrumbs->addItem("Detalle de " . $examen->getNombre());

		return $this->render('HistoriaBundle:Examen:show.html.twig',array('examen' => $examen));
	}

	public function editAction($examen) {
		$em = $this->getDoctrine()->getEntityManager();
		$examen = $em->getRepository('HistoriaBundle:Examen')->find($examen);

		if (!$examen) {
			throw $this->createNotFoundException('El examen o procedimiento solicitado no existe');
		}

		$editForm = $this->createForm(new ExamenType(), $examen);

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Examen",$this->get("router")->generate("examen_list"));
		$breadcrumbs->addItem("Detalle",$this->get("router")->generate("examen_show",array("examen" => $examen->getId())));
		$breadcrumbs->addItem("Modificar " . $examen->getNombre());

		return $this->render('HistoriaBundle:Examen:edit.html.twig',array(
						'examen' => $examen,
						'edit_form' => $editForm->createView(),
						));
	}

	public function updateAction($examen) {
		$em = $this->getDoctrine()->getEntityManager();
		$examen = $em->getRepository('HistoriaBundle:Examen')->find($examen);

		if (!$examen) {
			throw $this
					->createNotFoundException('El examen solicitada no existe');
		}

		$editForm = $this->createForm(new ExamenType(), $examen);
		$request = $this->getRequest();
		$editForm->bindRequest($request);

		if ($editForm->isValid()) {

			$em->persist($examen);
			$em->flush();

			$this->get('session')->setFlash('ok','El examen o procedimiento ha sido modificado éxitosamente.');
			return $this->redirect($this->generateUrl('examen_edit',array('examen' => $examen->getId())));
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Examen",$this->get("router")->generate("examen_list"));
		$breadcrumbs->addItem("Detalle",$this->get("router")->generate("examen_show",array("examen" => $examen->getId())));
		$breadcrumbs->addItem("Modificar " . $examen->getNombre());

		return $this->render('HistoriaBundle:Examen:edit.html.twig',array(
				'examen' => $examen,
				'edit_form' => $editForm->createView(),
			));
	}

	public function listAction() {
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Examen",$this->get("router")->generate("examen_list"));
		$breadcrumbs->addItem("Listado");

		$em = $this->getDoctrine()->getEntityManager();
		$form = $this->createForm(new SearchType());
		$examen = $em->getRepository('HistoriaBundle:Examen')->findBy(array(), array('nombre' => 'ASC'));

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($examen, $this->getRequest()->query->get('page', 1),10);

		return $this->render('HistoriaBundle:Examen:list.html.twig',array(
						'examenes' => $pagination, 'filtro' => 1,
						'search_form' => $form->createView(),
					));
	}

	public function searchAction() {
		$request = $this->getRequest();
		$form = $this->createForm(new SearchType());
		$form->bindRequest($request);
		$examen = null;

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio",$this->get("router")->generate("paciente_filtro"));
		$breadcrumbs->addItem("Examen",$this->get("router")->generate("examen_list"));
		$breadcrumbs->addItem("Listado");

		if ($form->isValid()) {
			$nombre = $form->get('nombre')->getData();
			$em = $this->getDoctrine()->getEntityManager();

			$dql = $em->createQuery("SELECT e FROM HistoriaBundle:Examen e WHERE e.nombre LIKE :nombre");
			$dql->setParameter('nombre', $nombre . '%');
			$examen = $dql->getResult();

			if (!$examen) {
				$this->get('session')->setFlash('error','Busquedad no exítosa, vuelva a intentar.');
				return $this->redirect($this->generateUrl('examen_list'));
			}
		}

		return $this->render('HistoriaBundle:Examen:list.html.twig',array(
				'examenes' => $examen, 'filtro' => 0,
				'search_form' => $form->createView(),
			));
	}
}

