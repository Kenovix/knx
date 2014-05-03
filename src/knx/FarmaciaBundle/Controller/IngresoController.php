<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Ingreso;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Imv;
use knx\FarmaciaBundle\Form\IngresoType;
use knx\FarmaciaBundle\Form\IngresoSearchType;
use Symfony\Component\HttpFoundation\Response;



class IngresoController extends Controller
{

	public function searchAction(){

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Ingresos");
		$breadcrumbs->addItem("Busqueda");

		$form   = $this->createForm(new IngresoSearchType());

		return $this->render('FarmaciaBundle:Ingreso:search.html.twig', array(
				'form'   => $form->createView()

		));

	}

	public function listAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem("Listado");

    	$paginator  = $this->get('knp_paginator');


    	$em = $this->getDoctrine()->getEntityManager();
        $ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->findAll();
        if (!$ingreso) {
        	$this->get('session')->setFlash('info', 'No existen ingresos creadas');
        }

       // $inventarios = $em->getRepository('FarmaciaBundle:Inventario')->findOneBy(array('ingreso' => $ingreso->getId()));

        //die(var_dump($inventarios));
       // $proveedor = $inventarios->get
        $ingreso = $paginator->paginate($ingreso,$this->getRequest()->query->get('page', 1), 8);

        return $this->render('FarmaciaBundle:Ingreso:list.html.twig', array(

        		'ingreso'  => $ingreso,
        ));
        }


    public function resultAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->findAll();
        $request = $this->get('request');
    	$fecha_inicio = $request->request->get('fecha_inicio');
    	$fecha_fin = $request->request->get('fecha_fin');
    	$num_fact = $request->request->get('num_fact');

    	//die(var_dump($num_fact));

    	if(trim($fecha_inicio)){
    		$desde = explode('/',$fecha_inicio);
    		//die(print_r($desde));


    		if(!checkdate($desde[1],$desde[0],$desde[2])){
    			$this->get('session')->setFlash('info', 'La fecha de inicio ingresada es incorrecta.');
    			 return $this->render('FarmaciaBundle:Ingreso:list.html.twig', array(
                'ingreso'  => $ingreso
        			));

    		}
    	}else{
    		$this->get('session')->setFlash('info', 'La fecha de inicio no puede estar en blanco.');
    		 return $this->render('FarmaciaBundle:Ingreso:list.html.twig', array(
                'ingreso'  => $ingreso
        		));

    		 $this->get('session')->setFlash('info',$this->get('sessio', 'La fecha de finalización ingresada es incorrecta.'));
    	}

    	if(trim($fecha_fin)){
    		$hasta = explode('/',$fecha_fin);

    		if(!checkdate($hasta[1],$hasta[0],$hasta[2])){
    			 return $this->render('FarmaciaBundle:Ingreso:list.html.twig', array(
                'ingreso'  => $ingreso
        ));
    		}
    	}else{
    		$this->get('session')->setFlash('info', 'La fecha de finalización no puede estar en blanco.');
    		 return $this->render('FarmaciaBundle:Ingreso:list.html.twig', array(
                'ingreso'  => $ingreso
        ));
    	}

    	$paginator  = $this->get('knp_paginator');
    	$where = "";
    	if(trim($num_fact)){

    		$where = "AND f.numFact = :num_fact ";

    	}


        		$query = "SELECT f FROM FarmaciaBundle:Ingreso f WHERE
    				f.fecha >= :inicio AND
			    	f.fecha <= :fin ".$where."
    				ORDER BY
    				f.fecha ASC";

    		$dql = $em->createQuery($query);



    		//die(print_r($dql));

    		$dql->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    		$dql->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');

    		if(trim($num_fact)){

    		$dql->setParameter('num_fact',$num_fact);

    		}

    		$ingreso = $dql->getResult();
    		//die(var_dump($ingreso));
    		//die("paso");
    		//$ingreso = $paginator->paginate($ingreso,$this->getRequest()->query->get('page', 1), 3);

    		if(!$ingreso)
    		{
    			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    			return $this->redirect($this->generateUrl('ingreso_search'));
    		}

    		return $this->render('FarmaciaBundle:Ingreso:list.html.twig', array(
    				'ingreso' => $ingreso,
    		));
    	}




    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem("Nueva Ingreso");

    	$ingreso = new Ingreso();
    	$ingreso->setFecha(new \datetime('now'));
        $ingreso->setEstado('A');

    	$form   = $this->createForm(new IngresoType(), $ingreso);

    	return $this->render('FarmaciaBundle:Ingreso:new.html.twig', array(
    			'ingreso'=>$ingreso,
    			'form'   => $form->createView()
    	));
    }


    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem("Nueva Ingreso");

    	$ingreso = new Ingreso();

    	$request = $this->getRequest();
    	$form   = $this->createForm(new IngresoType(), $ingreso);
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {

    			$valor_neto = $ingreso->getValorN();
    			$valor_total = $ingreso->getValorT();
    			if ($valor_total<$valor_neto){

    				$this->get('session')->setFlash('error', 'Valor Total no puede ser menor a Valor Neto.');
    				return $this->render('FarmaciaBundle:Ingreso:new.html.twig', array(
    						'form'   => $form->createView()
    				));
    			}else {


    			$em = $this->getDoctrine()->getEntityManager();
                        $ingreso->setEstado('A');

    			$em->persist($ingreso);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'El ingreso ha sido creada éxitosamente.');

    			return $this->redirect($this->generateUrl('ingreso_show', array("ingreso" => $ingreso->getId())));
    			}
    		}

    	}

    	return $this->render('FarmaciaBundle:Ingreso:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }

    public function showAction($ingreso)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
    	$paginator  = $this->get('knp_paginator');



    	if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}

    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findBy(array('ingreso' => $ingreso->getId()));
    	//$inventario = new Inventario();
    	$inventario = $paginator->paginate($inventario,$this->getRequest()->query->get('page', 1), 10);


    	//$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findByIngreso($ingreso);

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($ingreso->getnumFact());

    	return $this->render('FarmaciaBundle:Ingreso:show.html.twig', array(
    			'ingreso'  => $ingreso,
    			'inventario' => $inventario

    	));
    }

    public function editAction($ingreso)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);

   	   if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($ingreso->getnumFact(), $this->get("router")->generate("ingreso_show", array("ingreso" => $ingreso->getId())));
    	$breadcrumbs->addItem("Modificar".$ingreso->getnumFact());

    	$form   = $this->createForm(new IngresoType(), $ingreso);

    	return $this->render('FarmaciaBundle:Ingreso:edit.html.twig', array(
    			'ingreso' => $ingreso,
    			'form' => $form->createView(),
    	));
    }


    public function updateAction($ingreso)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);

        if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}

    	$form = $this->createForm(new IngresoType(), $ingreso);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {

    			$em = $this->getDoctrine()->getEntityManager();

    			$em->persist($ingreso);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'El ingreso ha sido modificado éxitosamente.');

    			return $this->redirect($this->generateUrl('ingreso_show', array("ingreso" => $ingreso->getId())));
    		}
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($ingreso->getnumFact(), $this->get("router")->generate("ingreso_show", array("ingreso" => $ingreso->getId())));
    	$breadcrumbs->addItem("Modificar".$ingreso->getnumFact());

    	return $this->render('FarmaciaBundle:Ingreso:new.html.twig', array(
       			'ingreso' => $ingreso,
    			'form' => $form->createView(),
    	));
    }

    public function printAction($ingreso) {

    	$em = $this->getDoctrine()->getEntityManager();
    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);
        $ingreso->setEstado('I');
        $em->persist($ingreso);
    	$em->flush();

    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findBy(array('ingreso' => $ingreso->getId()));
		
    	$pdf = $this->get('white_october.tcpdf')->create();
    	 
    	$html = $this->renderView('FarmaciaBundle:Ingreso:imprimir.html.twig',array(
    			'ingreso' =>$ingreso,
    			'inventarios' => $inventario));
    	 
    	return $pdf->quick_pdf($html, 'Detallado_ingreso_'.$ingreso->getId().'.pdf', 'D');
    }

}