<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\CategoriaImv;
use knx\FarmaciaBundle\Entity\Farmacia;
use knx\FarmaciaBundle\Form\CategoriaImvType;
use knx\FarmaciaBundle\Form\FarmaciaType;
use Symfony\Component\HttpFoundation\Response;


class CatfarmaController extends Controller
{
	public function catListAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("categoria_list"));
    	$breadcrumbs->addItem("Listado");
    	$paginator  = $this->get('knp_paginator');

    	$em = $this->getDoctrine()->getEntityManager();
        $catImv = $em->getRepository('FarmaciaBundle:CategoriaImv')->findAll();

        if (!$catImv) {
        	$this->get('session')->setFlash('info', 'No existen categorias creadas');
        }

        $catImv = $paginator->paginate($catImv,$this->getRequest()->query->get('page', 1), 10);

        return $this->render('FarmaciaBundle:CategoriaImv:list.html.twig', array(
                'catImv'  => $catImv
        ));
    }

    public function catNewAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("categoria_list"));
    	$breadcrumbs->addItem("Nueva CategoriaImv");

    	$catImv = new CategoriaImv();
    	$form   = $this->createForm(new CategoriaImvType(), $catImv);

    	return $this->render('FarmaciaBundle:CategoriaImv:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }


    public function catSaveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");

    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("categoria_list"));
    	$breadcrumbs->addItem("Nueva CategoriaImv");

    	$catImv = new CategoriaImv();

    	$request = $this->getRequest();
    	$form   = $this->createForm(new CategoriaImvType(), $catImv);
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {

    			$em = $this->getDoctrine()->getEntityManager();

    			$em->persist($catImv);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'La categoria ha sido creada éxitosamente.');

    			return $this->redirect($this->generateUrl('categoria_show', array("catImv" => $catImv->getId())));
    		}
    	}

    	return $this->render('FarmaciaBundle:CategoriaImv:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }

    public function catShowAction($catImv)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$catImv = $em->getRepository('FarmaciaBundle:CategoriaImv')->find($catImv);

    	if (!$catImv) {
    		throw $this->createNotFoundException('La categoria solicitada no esta disponible.');
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("categoria_list"));
    	$breadcrumbs->addItem($catImv->getNombre());

    	return $this->render('FarmaciaBundle:CategoriaImv:show.html.twig', array(
    			'catImv'  => $catImv
    	));
    }

    public function catEditAction($catImv)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$catImv = $em->getRepository('FarmaciaBundle:CategoriaImv')->find($catImv);

   	   if (!$catImv) {
    		throw $this->createNotFoundException('La categoria solicitada no esta disponible.');
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("categoria_list"));
    	$breadcrumbs->addItem($catImv->getNombre(), $this->get("router")->generate("categoria_show", array("catImv" => $catImv->getId())));
    	$breadcrumbs->addItem("Modificar".$catImv->getNombre());

    	$form   = $this->createForm(new CategoriaImvType(), $catImv);

    	return $this->render('FarmaciaBundle:CategoriaImv:edit.html.twig', array(
    			'catImv' => $catImv,
    			'form' => $form->createView(),
    	));
    }


    public function catUpdateAction($catImv)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$catImv = $em->getRepository('FarmaciaBundle:CategoriaImv')->find($catImv);

        if (!$catImv) {
    		throw $this->createNotFoundException('La categoria solicitada no esta disponible.');
    	}

    	$form = $this->createForm(new CategoriaImvType(), $catImv);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {

    			$em = $this->getDoctrine()->getEntityManager();

    			$em->persist($catImv);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'La categoria ha sido creada éxitosamente.');

    			return $this->redirect($this->generateUrl('categoria_show', array("catImv" => $catImv->getId())));
    		}
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("categoria_list"));
    	$breadcrumbs->addItem($catImv->getNombre(), $this->get("router")->generate("categoria_show", array("catImv" => $catImv->getId())));
    	$breadcrumbs->addItem("Modificar".$catImv->getNombre());

    	return $this->render('FarmaciaBundle:CategoriaImv:new.html.twig', array(
       			'catImv' => $catImv,
    			'form' => $form->createView(),
    	));
    }


    public function farmaciaListAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("farmacia_list"));
    	$breadcrumbs->addItem("Listado");
    	$paginator  = $this->get('knp_paginator');

    	$em = $this->getDoctrine()->getEntityManager();
    	$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->findAll();

    	if (!$farmacia) {
    		$this->get('session')->setFlash('info', 'No existen farmacias creadas');
    	}

    	$farmacia = $paginator->paginate($farmacia,$this->getRequest()->query->get('page', 1), 5);

    	return $this->render('FarmaciaBundle:Farmacia:list.html.twig', array(
    			'farmacia'  => $farmacia
    	));
    }

    public function farmaciaNewAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("farmacia_list"));
    	$breadcrumbs->addItem("Nueva Farmacia");

    	$farmacia = new Farmacia();
    	$form   = $this->createForm(new FarmaciaType(), $farmacia);

    	return $this->render('FarmaciaBundle:Farmacia:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }

    public function farmaciaSaveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");

    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("farmacia_list"));
    	$breadcrumbs->addItem("Nueva Farmacia");

    	$farmacia = new Farmacia();

    	$request = $this->getRequest();
    	$form   = $this->createForm(new FarmaciaType(), $farmacia);

    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {

    			$em = $this->getDoctrine()->getEntityManager();

    			$em->persist($farmacia);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'La Farmacia ha sido creada éxitosamente.');

    			return $this->redirect($this->generateUrl('farmacia_show', array("farmacia" => $farmacia->getId())));
    		}
    	}

    	return $this->render('FarmaciaBundle:Farmacia:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }


    public function farmaciaShowAction($farmacia)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->find($farmacia);

    	if (!$farmacia) {
    		throw $this->createNotFoundException('La Farmacia solicitada no esta disponible.');
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("farmacia_list"));
    	$breadcrumbs->addItem($farmacia->getNombre());

    	return $this->render('FarmaciaBundle:Farmacia:show.html.twig', array(
    			'farmacia'  => $farmacia
    	));
    }

    public function farmaciaEditAction($farmacia)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->find($farmacia);

    	if (!$farmacia) {
    		throw $this->createNotFoundException('La farmacia solicitada no esta disponible.');
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("farmacia_list"));
    	$breadcrumbs->addItem($farmacia->getNombre(), $this->get("router")->generate("farmacia_show", array("farmacia" => $farmacia->getId())));
    	$breadcrumbs->addItem("Modificar".$farmacia->getNombre());

    	$form   = $this->createForm(new FarmaciaType(), $farmacia);

    	return $this->render('FarmaciaBundle:Farmacia:edit.html.twig', array(
    			'farmacia' => $farmacia,
    			'form' => $form->createView(),
    	));
    }


    public function farmaciaUpdateAction($farmacia)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->find($farmacia);

    	if (!$farmacia) {
    		throw $this->createNotFoundException('La farmacia solicitada no esta disponible.');
    	}

    	$form = $this->createForm(new FarmaciaType(), $farmacia);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {

    			$em = $this->getDoctrine()->getEntityManager();

    			$em->persist($farmacia);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'La Farmacia ha sido creada éxitosamente.');

    			return $this->redirect($this->generateUrl('farmacia_show', array("farmacia" => $farmacia->getId())));
    		}
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("farmacia_list"));
    	$breadcrumbs->addItem($farmacia->getNombre(), $this->get("router")->generate("farmacia_show", array("farmacia" => $farmacia->getId())));
    	$breadcrumbs->addItem("Modificar".$farmacia->getNombre());

    	return $this->render('FarmaciaBundle:Farmacia:new.html.twig', array(
    			'farmacia' => $farmacia,
    			'form' => $form->createView(),
    	));
    }

    public function farmaciaResultfAction($farmacia)
    {
    		$em = $this->getDoctrine()->getEntityManager();
    		$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->find($farmacia);
    		    		//die(var_dump($imv));


    		$query = "SELECT a FROM FarmaciaBundle:ImvFarmacia a WHERE ";
    		$parametros = array();

    		if($farmacia){
    			$query .= "a.farmacia = :farmacia AND ";
    			$parametros["farmacia"] = $farmacia;
    		}


    		$query = substr($query, 0, strlen($query)-4);

    		$query .= " ORDER BY a.imv ASC";

    		$dql = $em->createQuery($query);
    		$dql->setParameters($parametros);

    		$farmacia1 = $dql->getResult();

    		if(!$farmacia1)
    		{
    			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    			return $this->redirect($this->generateUrl('farmacia_list'));
    		}

    		$breadcrumbs = $this->get("white_october_breadcrumbs");
    		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    		$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("farmacia_list"));
    		$breadcrumbs->addItem($farmacia->getNombre(), $this->get("router")->generate("farmacia_show", array("farmacia" => $farmacia->getId())));

    		return $this->render('FarmaciaBundle:Farmacia:result.html.twig', array(
    				'farmacia' =>$farmacia,
    				'farmacia1' => $farmacia1,

    		));

    }

    public function farmaciaPrintAction($farmacia) {

    		$em = $this->getDoctrine()->getEntityManager();
    		$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->find($farmacia);
    		    		//die(var_dump($imv));


    		$query = "SELECT a FROM FarmaciaBundle:ImvFarmacia a WHERE ";
    		$parametros = array();

    		if($farmacia){
    			$query .= "a.farmacia = :farmacia AND ";
    			$parametros["farmacia"] = $farmacia;
    		}


    		$query = substr($query, 0, strlen($query)-4);

    		$query .= " ORDER BY a.imv ASC";

    		$dql = $em->createQuery($query);
    		$dql->setParameters($parametros);

    		$farmacia1 = $dql->getResult();



    	$pdf = $this->get('white_october.tcpdf')->create();
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('dejavusans', '', 9, '', true);

		// Header and footer
		//$pdf->SetHeaderData('logo.jpg', 20, 'Hospital San Agustin');
		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
		$pdf->setHeaderFont(Array('dejavusans', '', 9));
		$pdf->setFooterFont(Array('dejavusans', '', 9));

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(1);
		$pdf->SetFooterMargin(10);

		// set image scale factor
		//$pdf->setImageScale(5);

		$pdf->AddPage();


		$farmacia = $this->renderView('FarmaciaBundle:Farmacia:listado.html.twig',array(
				'farmacia1'  => $farmacia1,
				'farmacia'   => $farmacia,
		));

		$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $farmacia,$border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

		$response = new Response($pdf->Output('listado.pdf', 'I'));
		$response->headers->set('Content-Type', 'application/pdf');
	}
}