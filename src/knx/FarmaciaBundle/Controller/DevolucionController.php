<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Devolucion;
use knx\FarmaciaBundle\Entity\Proveedor;
use knx\FarmaciaBundle\Entity\AlmacenImv;
use knx\FarmaciaBundle\Entity\Imv;
use knx\ParametrizarBundle\Entity\Almacen;
use knx\FarmaciaBundle\Form\DevolucionType;
use knx\FarmaciaBundle\Form\DevolucionSearchType;
use Symfony\Component\HttpFoundation\Response;



class DevolucionController extends Controller
{

	public function searchAction(){

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Farmacia");
		$breadcrumbs->addItem("Busqueda");

		$form   = $this->createForm(new DevolucionSearchType());

		return $this->render('FarmaciaBundle:Devolucion:search.html.twig', array(
				'form'   => $form->createView()

		));

	}


	public function searchprintAction(){

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Farmacia");
		$breadcrumbs->addItem("Busqueda");

		$form   = $this->createForm(new DevolucionSearchType());

		return $this->render('FarmaciaBundle:Devolucion:searchprint.html.twig', array(
				'form'   => $form->createView()

		));

	}


	public function listAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devoluciones", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem("Listado");
    	$paginator  = $this->get('knp_paginator');

    	$em = $this->getDoctrine()->getEntityManager();
        $devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findAll();

        if (!$devolucion) {
        	$this->get('session')->setFlash('info', 'No existen devoluciones');
        }


        $devolucion = $paginator->paginate($devolucion,$this->getRequest()->query->get('page', 1), 10);


        return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
                'devfarma'  => $devolucion

        ));
    }


    public function resultAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findAll();
    	$request = $this->get('request');
    	$fecha_inicio = $request->request->get('fecha_inicio');
    	$fecha_fin = $request->request->get('fecha_fin');


    	if(trim($fecha_inicio)){
    		$desde = explode('/',$fecha_inicio);

    		//die(print_r($desde));

    	if(!checkdate($desde[1],$desde[0],$desde[2])){
				$this->get('session')->setFlash('info', 'La fecha de inicio ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Devolucion:search.html.twig', array(
				'form'   => $form->createView()

			));

			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de inicio no puede estar en blanco.');
			return $this->render('FarmaciaBundle:Devolucion:search.html.twig', array(
				'form'   => $form->createView()

			));
		}

		if(trim($fecha_fin)){
			$hasta = explode('/',$fecha_fin);

			if(!checkdate($hasta[1],$hasta[0],$hasta[2])){
				$this->get('session')->setFlash('info', 'La fecha de finalización ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Devolucion:search.html.twig', array(
				'form'   => $form->createView()

			));
			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de finalización no puede estar en blanco.');
				return $this->render('FarmaciaBundle:Devolucion:search.html.twig', array(
				'form'   => $form->createView()

			));
		}

    	$query = "SELECT f FROM FarmaciaBundle:Devolucion f WHERE
    				f.fecha >= :inicio AND
			    	f.fecha <= :fin
    				ORDER BY
    				f.fecha ASC";

    	$dql = $em->createQuery($query);



    	//die(print_r($dql));

    	$dql->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    	$dql->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');

    	$devolucion = $dql->getResult();
    	//die(var_dump($ingreso));
    	//die("paso");

    	if(!$devolucion)
    	{
    		$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    		return $this->redirect($this->generateUrl('devolucion_search'));
    	}

    	return $this->render('FarmaciaBundle:Devolucion:list.html.twig', array(
    			'devfarma' => $devolucion,
    	));
    }

    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devolucions", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem("Nueva Devolucion");
    	$em = $this->getDoctrine()->getEntityManager();
    	$devolucion = new Devolucion();
    	$devolucion->setFecha(new \datetime('now'));
    	$form   = $this->createForm(new DevolucionType(), $devolucion);

    	return $this->render('FarmaciaBundle:Devolucion:new.html.twig', array(
    			'devolucion'=>$devolucion,
    			'form'   => $form->createView()
    	));
    }


    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("devolucion_list"));
    	$breadcrumbs->addItem("Nueva Devolucion");
    	$em = $this->getDoctrine()->getEntityManager();

    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findAll();

    	$devolucion = new Devolucion();
    	$devolucion->setFecha(new \datetime('now'));


    	$request = $this->getRequest();
    	$form   = $this->createForm(new DevolucionType(), $devolucion);
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);



    		if ($form->isValid()) {

    			$cant_devolucion = $devolucion->getCant();/*cantidad de devolucion*/
    			$imv_ingresasdo =  $devolucion->getImv();

    			$almacen_ingresado = $devolucion->getAlmacen();
    			$cant_imv = $imv_ingresasdo->getCantT();/*traigo cantidad total del imv*/
    			$proveedor_ingresado = $devolucion->getProveedor();

    			//$cant_inventario = $inventario->getCant();/*cantidad inventario*/
    			//$ingreso = $inventario->getIngreso();
    			//die(var_dump($ingreso));
    			//$imv = $imvingresasdo->getImv();/*Entidad imv para llegar a la cantidad total*/

    			//$proveedor = $ingreso->getProveedor();
    			//$precio_compra = $inventario->getPrecioCompra();

    			//$inventariop = $devolucion->getInventario();/*Entidad inventario*/
    			//$cant_inventariop = $inventariop->getCant();/*cantidad inventario*/

    			$em = $this->getDoctrine()->getEntityManager();

    			$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findoneBy(array('almacen'=> $almacen_ingresado, 'imv' => $imv_ingresasdo));


					if ($almacenimv){

						$cant_almacenimv = $almacenimv->getCant();
					}else{

    					$this->get('session')->setFlash('error','Item para devolucion no corresponde a Almacen selecionado-'.$almacen_ingresado = $devolucion->getAlmacen());
    					return $this->redirect($this->generateUrl('devolucion_new'));
    				}


    				if ($cant_almacenimv < $cant_devolucion){

    					$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en almacen='.$cant_almacenimv = $almacenimv->getCant().'');
    					return $this->redirect($this->generateUrl('devolucion_new'));
    				}
    				else {


    					$imv_ingresasdo->setCantT($cant_imv-$cant_devolucion);
    					$almacenimv->setCant($cant_almacenimv - $cant_devolucion);

    					//die(var_dump($imv_ingresasdo));
    					$em->persist($devolucion);
    					$em->persist($imv_ingresasdo);
    					$em->persist($almacenimv);

    					$em->flush();

    					$this->get('session')->setFlash('ok', 'El devolucion ha sido creada éxitosamente.');

    					return $this->redirect($this->generateUrl('devolucion_show', array('devolucion' => $devolucion->getId())));

    				}
    					//$inventario->setCant($cant_inventario-$cant_devolucion);

    				}


    				
    	}
        
                                  return $this->render('FarmaciaBundle:Devolucion:new.html.twig', array(
    						'form'   => $form->createView()
                                        ));
	}




    public function showAction($devolucion)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->find($devolucion);

       	$imv = $devolucion->getImv();/*Entidad inventario*/


    	if (!$devolucion) {
    		throw $this->createNotFoundException('El devolucion solicitado no esta disponible.');
    	}


    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Devolucions", $this->get("router")->generate("devolucion_list"));
    	//$breadcrumbs->addItem($devolucion->getId());

    	return $this->render('FarmaciaBundle:Devolucion:show.html.twig', array(
    			'devfarma'  => $devolucion,
    			'imv' => $imv


    	));
    }


    public function printAction()
    {


    $em = $this->getDoctrine()->getEntityManager();
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findAll();
    	$request = $this->get('request');
    	$fecha_inicio = $request->request->get('fecha_inicio');
    	$fecha_fin = $request->request->get('fecha_fin');


    	if(trim($fecha_inicio)){
    		$desde = explode('/',$fecha_inicio);

    		//die(print_r($desde));

    	if(!checkdate($desde[1],$desde[0],$desde[2])){
				$this->get('session')->setFlash('info', 'La fecha de inicio ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Devolucion:searchprint.html.twig', array(
				'form'   => $form->createView()

			));

			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de inicio no puede estar en blanco.');
			return $this->render('FarmaciaBundle:Devolucion:searchprint.html.twig', array(
				'form'   => $form->createView()

			));
		}

		if(trim($fecha_fin)){
			$hasta = explode('/',$fecha_fin);

			if(!checkdate($hasta[1],$hasta[0],$hasta[2])){
				$this->get('session')->setFlash('info', 'La fecha de finalización ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Devolucion:searchprint.html.twig', array(
				'form'   => $form->createView()

			));
			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de finalización no puede estar en blanco.');
				return $this->render('FarmaciaBundle:Devolucion:searchprint.html.twig', array(
				'form'   => $form->createView()

			));
		}

    	$query = "SELECT f FROM FarmaciaBundle:Devolucion f WHERE
    				f.fecha >= :inicio AND
			    	f.fecha <= :fin
    				ORDER BY
    				f.fecha ASC";

    	$dql = $em->createQuery($query);



    	//die(print_r($dql));

    	$dql->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    	$dql->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');

    	$devolucion = $dql->getResult();
    	//die(var_dump($ingreso));
    	//die("paso");

    	if(!$devolucion)
    	{
    		$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    		return $this->redirect($this->generateUrl('devolucion_search'));
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


    	$html = $this->renderView('FarmaciaBundle:Devolucion:listado.html.twig',array(
    			'devfarma'  => $devolucion,
    			'fechai' =>$fecha_inicio,
    			'fechaf' =>$fecha_fin

    	));

    	$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html,$border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

    	$response = new Response($pdf->Output('listado.pdf', 'I'));
    	$response->headers->set('Content-Type', 'application/pdf');
    }


}