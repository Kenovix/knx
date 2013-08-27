<?php

namespace knx\FarmaciaBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\AlmacenImv;
use knx\FarmaciaBundle\Form\SearchAlmacenType;
use knx\ParametrizarBundle\Entity\Almacen;
use Symfony\Component\HttpFoundation\Response;





class AlmacenImvController extends Controller
{


   public function searchAction()
    {

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Almacen");
    	$breadcrumbs->addItem("Busqueda");

    	$form   = $this->createForm(new SearchAlmacenType());

    	$em = $this->getDoctrine()->getEntityManager();
    	$almacen = $em->getRepository('ParametrizarBundle:Almacen')->findAll();

    	if (!$almacen) {
    		$this->get('session')->setFlash('info', 'no existen  ingresos');
    	}

    	return $this->render('FarmaciaBundle:AlmacenImv:search.html.twig', array(
    			'form'   => $form->createView()
    	));
    }


    public function searchprintAction()
    {

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Almacen");
    	$breadcrumbs->addItem("Busqueda");

    	$form   = $this->createForm(new SearchAlmacenType());

    	$em = $this->getDoctrine()->getEntityManager();
    	$almacen = $em->getRepository('ParametrizarBundle:Almacen')->findAll();

    	if (!$almacen) {
    		$this->get('session')->setFlash('info', 'no existen  ingresos');
    	}

    	return $this->render('FarmaciaBundle:AlmacenImv:searcha.html.twig', array(
    			'form'   => $form->createView()
    	));
    }


    public function listAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Almacen");
    	$breadcrumbs->addItem("Busqueda", $this->get("router")->generate("almacenimv_searcha"));
    	$breadcrumbs->addItem("Resultado");

    	$form   = $this->createForm(new SearchAlmacenType());
    	$request = $this->getRequest();
    	$form->bindRequest($request);

    	$almacen = $form->get('almacen')->getData();
    	//die(var_dump($almacen));

    	if(((trim($almacen) ))){

    		$em = $this->getDoctrine()->getEntityManager();
    		$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findOneBy(array('almacen' => $almacen));
    		//die(var_dump($imv));


    		$query = "SELECT a FROM FarmaciaBundle:AlmacenImv a WHERE ";
    		$parametros = array();

    		if($almacen){
    			$query .= "a.almacen = :almacen AND ";
    			$parametros["almacen"] = $almacen;
    		}


    		$query = substr($query, 0, strlen($query)-4);

    		$query .= " ORDER BY a.imv ASC";

    		$dql = $em->createQuery($query);
    		$dql->setParameters($parametros);

    		$almacenimv = $dql->getResult();

    		if(!$almacenimv)
    		{
    			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    			return $this->redirect($this->generateUrl('almacenimv_search'));
    		}

    		return $this->render('FarmaciaBundle:AlmacenImv:list.html.twig', array(
    				'almacenimv' => $almacenimv,
    				'form'   => $form->createView()
    		));
    	}else{
    		$this->get('session')->setFlash('error', 'Los parametros de busqueda ingresados son incorrectos.');

    		return $this->redirect($this->generateUrl('almacenimv_list'));
    	}
    }


    public function printAction()
    {
    	$form   = $this->createForm(new SearchAlmacenType());
    	$request = $this->getRequest();
    	$form->bindRequest($request);

    	$almacen = $form->get('almacen')->getData();
    	//die(var_dump($almacen));

    	if(((trim($almacen) ))){

    		$em = $this->getDoctrine()->getEntityManager();
    		$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findOneBy(array('almacen' => $almacen));
    		//die(var_dump($imv));


    		$query = "SELECT a FROM FarmaciaBundle:AlmacenImv a WHERE ";
    		$parametros = array();

    		if($almacen){
    			$query .= "a.almacen = :almacen AND ";
    			$parametros["almacen"] = $almacen;
    		}


    		$query = substr($query, 0, strlen($query)-4);

    		$query .= " ORDER BY a.imv ASC";

    		$dql = $em->createQuery($query);
    		$dql->setParameters($parametros);

    		$almacenimv = $dql->getResult();

    		if(!$almacenimv)
    		{
    			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    			return $this->redirect($this->generateUrl('almacenimv_search'));
    		}

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


    	$html = $this->renderView('FarmaciaBundle:AlmacenImv:listado.html.twig',array(
    			'almacenimv'  => $almacenimv,
    			'almacen' =>$almacen


    	));

    	$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html,$border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

    	$response = new Response($pdf->Output('listado.pdf', 'I'));
    	$response->headers->set('Content-Type', 'application/pdf');

    	}else{
    		$this->get('session')->setFlash('error', 'Los parametros de busqueda ingresados son incorrectos.');

    		return $this->redirect($this->generateUrl('almacenimv_list'));
    	}
    }

}