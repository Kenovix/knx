<?php

namespace knx\FarmaciaBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Imv;
use knx\FarmaciaBundle\Form\ImvType;
use knx\FarmaciaBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Response;




class ImvController extends Controller
{


   public function searchAction()
    {
    	$form   = $this->createForm(new SearchType());

    	$em = $this->getDoctrine()->getEntityManager();
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->findAll();

    	if (!$imv) {
    		$this->get('session')->setFlash('info', 'Stock sin ingresos');
    	}

    	return $this->render('FarmaciaBundle:Imv:search.html.twig', array(
    			'form'   => $form->createView()
    	));
    }

    public function searchimprimirAction()
    {
    	$form   = $this->createForm(new SearchType());

    	$em = $this->getDoctrine()->getEntityManager();
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->findAll();

    	if (!$imv) {
    		$this->get('session')->setFlash('info', 'Stock sin ingresos');
    	}

    	return $this->render('FarmaciaBundle:Imv:searchimprimir.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    public function listAction()
    {
    	$form   = $this->createForm(new SearchType());
    	$request = $this->getRequest();
    	$form->bindRequest($request);

    	$nombre = $form->get('nombre')->getData();
    	//die(var_dump($nombre));

    	$tipo = $form->get('tipo')->getData();
    	if(((trim($nombre) or trim($tipo)))){

    		$em = $this->getDoctrine()->getEntityManager();
    		$imv = $em->getRepository('FarmaciaBundle:Imv')->findOneBy(array('tipoImv' => $tipo));
    		//die(var_dump($imv));


    		$query = "SELECT i FROM FarmaciaBundle:Imv i WHERE ";
    		$parametros = array();

    		if($tipo){
    			$query .= "i.tipoImv = :tipo AND ";
    			$parametros["tipo"] = $tipo;
    		}


    		if($nombre){
    			$query .= "i.nombre LIKE :nombre AND ";
    			$parametros["nombre"] = $nombre.'%';
    		}


    		$query = substr($query, 0, strlen($query)-4);

    		$query .= " ORDER BY i.nombre ASC";

    		$dql = $em->createQuery($query);
    		$dql->setParameters($parametros);

    		$imv = $dql->getResult();

    		if(!$imv)
    		{
    			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    			return $this->redirect($this->generateUrl('imv_search'));
    		}

    		return $this->render('FarmaciaBundle:Imv:list.html.twig', array(
    				'imv' => $imv,
    				'form'   => $form->createView()
    		));
    	}else{
    		$this->get('session')->setFlash('error', 'Los parametros de busqueda ingresados son incorrectos.');

    		return $this->redirect($this->generateUrl('imv_search'));
    	}
    }

    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Stock", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem("Nueva Existencia");

    	//$tipo = $form->get('tipo')->getData();

    	$imv = new Imv();
    	$form   = $this->createForm(new ImvType(), $imv);

    	return $this->render('FarmaciaBundle:Imv:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }


    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem("Nueva Existencia");

    	$imv = new Imv();

    	$request = $this->getRequest();
    	$form   = $this->createForm(new ImvType(), $imv);
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {

    			$em = $this->getDoctrine()->getEntityManager();

    			$imv->setcantT('0');

    			$em->persist($imv);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'La existencia ha sido creada éxitosamente.');

    			return $this->redirect($this->generateUrl('imv_show', array("imv" => $imv->getId())));
    		}
    	}

    	return $this->render('FarmaciaBundle:Imv:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }

    public function showAction($imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find($imv);



    	if (!$imv) {
    		throw $this->createNotFoundException('La existencia solicitada no esta disponible.');
    	}

    	//$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findByImv($imv);

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Existencia", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem($imv->getcodAdmin());
    	//$breadcrumbs->addItem("Mostrar");

    	return $this->render('FarmaciaBundle:Imv:show.html.twig', array(
    			'imv'  => $imv

    	));
    }

    public function editAction($imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find($imv);

   	   if (!$imv) {
    		throw $this->createNotFoundException('La existencia solicitada no esta disponible.');
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Existencia", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem($imv->getcodCups(), $this->get("router")->generate("imv_show", array("imv" => $imv->getId())));
    	$breadcrumbs->addItem("Modificar".$imv->getcodCups());

    	$form   = $this->createForm(new ImvType(), $imv);

    	return $this->render('FarmaciaBundle:Imv:edit.html.twig', array(
    			'imv' => $imv,
    			'form' => $form->createView(),
    	));
    }


    public function updateAction($imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find($imv);

        if (!$imv) {
    		throw $this->createNotFoundException('La existencia solicitada no esta disponible.');
    	}

    	$form = $this->createForm(new ImvType(), $imv);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {

    			$em = $this->getDoctrine()->getEntityManager();

    			$em->persist($imv);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'La existencia ha sido modificado éxitosamente.');

    			return $this->redirect($this->generateUrl('imv_show', array("imv" => $imv->getId())));
    		}
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem($imv->getcodCups(), $this->get("router")->generate("imv_show", array("imv" => $imv->getId())));
    	$breadcrumbs->addItem("Modificar".$imv->getcodCups());

    	return $this->render('FarmaciaBundle:Imv:edit.html.twig', array(
       			'imv' => $imv,
    			'form' => $form->createView(),
    	));
    }

    public function printAction() {

    	$form   = $this->createForm(new SearchType());
    	$request = $this->getRequest();
    	$form->bindRequest($request);
    	$nombre = $form->get('nombre')->getData();

    	$tipo = $form->get('tipo')->getData();
    	//die(var_dump($tipo));

    	if(((trim($nombre) or trim($tipo)))){

    		$em = $this->getDoctrine()->getEntityManager();
    		$imv = $em->getRepository('FarmaciaBundle:Imv')->findOneBy(array('tipoImv' => $tipo));
    		//die(var_dump($imv));


    		$query = "SELECT i FROM FarmaciaBundle:Imv i WHERE ";
    		$parametros = array();

    		if($tipo){
    			$query .= "i.tipoImv = :tipo AND ";
    			$parametros["tipo"] = $tipo;
    		}


    		if($nombre){
    			$query .= "i.nombre LIKE :nombre AND ";
    			$parametros["nombre"] = $nombre.'%';
    		}


    		$query = substr($query, 0, strlen($query)-4);

    		$query .= " ORDER BY i.nombre ASC";

    		$dql = $em->createQuery($query);
    		$dql->setParameters($parametros);

    		$imv = $dql->getResult();



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


    	$imv = $this->renderView('FarmaciaBundle:Imv:listado.html.twig',array(
    			'imv'  => $imv,

    	));

    	$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $imv,$border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

    	$response = new Response($pdf->Output('listado.pdf', 'I'));
    	$response->headers->set('Content-Type', 'application/pdf');
   	 }


	}
}