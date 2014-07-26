<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Traslado;
use knx\FarmaciaBundle\Entity\Almacen;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Imv;
use knx\FarmaciaBundle\Entity\Farmacia;
use knx\FarmaciaBundle\Entity\ImvFarmacia;
use knx\FarmaciaBundle\Form\TrasladoType;
use knx\FarmaciaBundle\Form\TrasladoSearchType;
use Symfony\Component\HttpFoundation\Response;



class TrasladoController extends Controller
{

	public function searchAction(){

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Traslados");
		$breadcrumbs->addItem("Busqueda");



		$form   = $this->createForm(new TrasladoSearchType());

		return $this->render('FarmaciaBundle:Traslado:search.html.twig', array(
				'form'   => $form->createView()

		));

	}

	public function searchprintAction()
	{
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Traslados");
		$breadcrumbs->addItem("Busqueda");



		$form   = $this->createForm(new TrasladoSearchType());

		return $this->render('FarmaciaBundle:Traslado:searchprint.html.twig', array(
				'form'   => $form->createView()

		));
	}


	public function resultAction()
	{


		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Farmacia");
		$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
		$breadcrumbs->addItem("Resultado Busqueda");



		$em = $this->getDoctrine()->getEntityManager();
		$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
		$request = $this->get('request');
		$fecha_inicio = $request->request->get('fecha_inicio');
		$fecha_fin = $request->request->get('fecha_fin');
		//die(print_r($fecha_inicio));

		$form   = $this->createForm(new TrasladoSearchType());

		if(trim($fecha_inicio)){
			$desde = explode('/',$fecha_inicio);

			//die(print_r($desde));

			if(!checkdate($desde[1],$desde[0],$desde[2])){
				$this->get('session')->setFlash('info', 'La fecha de inicio ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Traslado:search.html.twig', array(
				'form'   => $form->createView()

			));

			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de inicio no puede estar en blanco.');
			return $this->render('FarmaciaBundle:Traslado:search.html.twig', array(
				'form'   => $form->createView()

			));
		}

		if(trim($fecha_fin)){
			$hasta = explode('/',$fecha_fin);

			if(!checkdate($hasta[1],$hasta[0],$hasta[2])){
				$this->get('session')->setFlash('info', 'La fecha de finalización ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Traslado:search.html.twig', array(
				'form'   => $form->createView()

			));
			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de finalización no puede estar en blanco.');
				return $this->render('FarmaciaBundle:Traslado:search.html.twig', array(
				'form'   => $form->createView()

			));
		}

		$query = "SELECT f FROM FarmaciaBundle:Traslado f WHERE
    				f.fecha >= :inicio AND
			    	f.fecha <= :fin
    				ORDER BY
    				f.fecha ASC";

		$dql = $em->createQuery($query);



		//die(print_r($dql));

		$dql->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
		$dql->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');

		$traslado = $dql->getResult();
		//die(var_dump($ingreso));
		//die("paso");

		if(!$traslado)
		{
			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

			return $this->redirect($this->generateUrl('traslado_search'));
		}

		return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
				'trasfarma'  => $traslado
		));
	}



	public function listAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Listado");

    	$paginator  = $this->get('knp_paginator');

    	$em = $this->getDoctrine()->getEntityManager();
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
    	$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->findAll();
    	$imvfarmacia = $em->getRepository('FarmaciaBundle:ImvFarmacia')->findAll();

		if (!$traslado) {
			$this->get('session')->setFlash('info', 'No existen traslados');
		}

        $traslado = $paginator->paginate($traslado,$this->getRequest()->query->get('page', 1), 10);


        return $this->render('FarmaciaBundle:Traslado:list.html.twig', array(
                'trasfarma'  => $traslado,
        		'farmacia'   => $farmacia,
        		'imvfarmacia' => $imvfarmacia
        ));
    }

    public function newAction($imv,$almacen)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Nueva Traslado");
    	$em = $this->getDoctrine()->getEntityManager();

    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find( $imv);
    	$almacen = $em->getRepository('ParametrizarBundle:Almacen')->find($almacen);

    	//die(var_dump($imv));
    	$nombre_imv = $imv->getNombre();
    	$nombre_almacen = $almacen->getNombre();
    	$traslado = new Traslado();

    	$traslado->setFecha(new \datetime('now'));
    	$form   = $this->createForm(new TrasladoType(), $traslado);
    	$imvf = $form["imv"]->setData($nombre_imv);
    	//$almacenf = $form["almacen"]->setData($nombre_almacen);

    	return $this->render('FarmaciaBundle:Traslado:new.html.twig', array(
    			'traslado'=>$traslado,
    			'imv'=>$imv,
    			'almacen'=>$almacen,
    			'form'   => $form->createView()
    	));
    }


    public function saveAction($imv,$almacen)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Nueva Traslado");
    	$em = $this->getDoctrine()->getEntityManager();

    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
    	$traslado = new Traslado();
    	$traslado->setFecha(new \datetime('now'));

    	$request = $this->getRequest();
    	$form   = $this->createForm(new TrasladoType(), $traslado);
    	$imvf = $form->get('imv')->getData();

    	$em = $this->getDoctrine()->getEntityManager();
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find( $imv);
    	$almacen = $em->getRepository('ParametrizarBundle:Almacen')->find($almacen);



    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {
    			$almacen = $em->getRepository('ParametrizarBundle:Almacen')->find( $almacen);

    			$trasladoimv = $em->getRepository('FarmaciaBundle:Traslado')->findoneBy(array('farmacia'=> $traslado->getFarmacia(),'imv' => $imv->getId()));
    			//die(print_r($trasladoimv));

    			$tipo_traslado = $traslado->getTipo();/*tipo de movimiento*/

    			 if($tipo_traslado=='D' && !$trasladoimv ){


    			 	$this->get('session')->setFlash('error','El medicamento no presenta traslados para realizar una devolucion deben existir traslados');
    			 	return $this->redirect($this->generateUrl('traslado_new', array("imv" => $imv->getId(),"almacen" => $almacen->getId())));

    			   }else{
    			   	$tipo_traslado = $traslado->getTipo();/*tipo de movimiento*/

    			   	$cant_traslado = $traslado->getCant();/*cantidad de traslado*/
    			   	$farmacia = $traslado->getFarmacia();/*Farmacia ingresada*/
    			   	//die(var_dump($cant_traslado));
    			   	//die(var_dump($cant_imv));


    			 }



    			$em = $this->getDoctrine()->getEntityManager();
    			$imv = $em->getRepository('FarmaciaBundle:Imv')->find( $imv);
    			$almacen = $em->getRepository('ParametrizarBundle:Almacen')->find( $almacen);
    			//die(var_dump($almacen));

    			$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findoneBy(array('almacen'=> $almacen->getId(), 'imv' => $imv->getId()));
    			$cant_almacenimv = $almacenimv->getCant();/*traigo cantidad total del almacen*/
				$almacenid = $almacen->getId();
    			//die(var_dump($almacenid));

    			if ($tipo_traslado=='T'){

    				if ($cant_almacenimv < $cant_traslado){

    				$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia almacen,cantidad en existencia='.$cant_almacenimv = $almacenimv->getCant().'');
    		        return $this->redirect($this->generateUrl('traslado_new', array("imv" => $imv->getId(),"almacen" => $almacen->getId())));



    				}else {
    					$imvfarmacia = $em->getRepository('FarmaciaBundle:ImvFarmacia')->findoneBy(array('farmacia'=> $traslado->getFarmacia(), 'imv' => $imv->getId()));
    					$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findoneBy(array('almacen'=> $almacen->getId(), 'imv' => $imv->getId()));

    					if($imvfarmacia){

    						$cant_traslado = $traslado->getCant();/*cantidad de traslado*/
    						$cant_imvfarmacia = $imvfarmacia->getCant();/*cantidad de imvfarmacia*/
    						$cant_almacenimv = $almacenimv->getCant();/*traigo cantidad total del almacen*/

    						$traslado->setImv($imv);
    						$traslado->setAlmacen($almacen);


    						$imvfarmacia->setCant($cant_traslado + $cant_imvfarmacia);
						$almacenimv->setCant($cant_almacenimv - $cant_traslado);
    						$em->persist($imvfarmacia);
    						$em->persist($almacenimv);
    						$em->persist($traslado);


    						$em->flush();
    						$this->get('session')->setFlash('ok', 'El traslado ha sido creada éxitosamente.');
    						return $this->redirect($this->generateUrl('traslado_list'));

    					}else{
    						$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findoneBy(array('almacen'=> $almacen->getId(), 'imv' => $imv->getId()));
    						$cant_almacenimv = $almacenimv->getCant();/*traigo cantidad total del almacen*/
    						$cant_traslado = $traslado->getCant();/*cantidad de traslado*/

    						$imvfarmacia = new ImvFarmacia();
    						$traslado->setImv($imv);
    						$traslado->setAlmacen($almacen);
    						$imvfarmacia->setImv($imv);
    						$imvfarmacia->setFarmacia($farmacia);
    						$imvfarmacia->setCant($cant_traslado);
    						$almacenimv->setCant($cant_almacenimv - $cant_traslado);

    						$em->persist($imvfarmacia);
    						$em->persist($almacenimv);




    					}
    					$em->persist($traslado);

          					$em->flush();

    					$this->get('session')->setFlash('ok', 'El traslado ha sido creada éxitosamente.');
    					return $this->redirect($this->generateUrl('traslado_show', array("traslado" => $traslado->getId())));
     					}

    			}
    			elseif (($tipo_traslado=='D')){


    						$imvfarmacia = $em->getRepository('FarmaciaBundle:ImvFarmacia')->findoneBy(array('farmacia'=> $traslado->getFarmacia(), 'imv' => $imv->getId()));

    						$cant_traslado = $traslado->getCant();/*cantidad de traslado*/
    						$cant_imvfarmacia = $imvfarmacia->getCant();/*cantidad de imvfarmacia*/

    						//die(var_dump($cant_imvfarmacia));

    						if($cant_imvfarmacia < $cant_traslado ){
    							$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia de la farmacia,cantidad en farmacia='.$cant_imvfarmacia = $imvfarmacia->getCant().'');
    		      			  return $this->redirect($this->generateUrl('traslado_new', array("imv" => $imv->getId(),"almacen" => $almacen->getId())));
    						}else{
    						$imvfarmacia = $em->getRepository('FarmaciaBundle:ImvFarmacia')->findoneBy(array('farmacia'=> $traslado->getFarmacia(), 'imv' => $imv->getId()));

    						if($imvfarmacia){

    						$cant_traslado = $traslado->getCant();/*cantidad de traslado*/
    						$cant_imvfarmacia = $imvfarmacia->getCant();/*cantidad de imvfarmacia*/
    						$cant_almacenimv = $almacenimv->getCant();/*traigo cantidad total del almacen*/

    						$traslado->setImv($imv);
    						$traslado->setAlmacen($almacen);

    						$imvfarmacia->setCant($cant_imvfarmacia - $cant_traslado);
    						$almacenimv->setCant($cant_almacenimv + $cant_traslado);

    						$em->persist($imvfarmacia);
    						$em->persist($almacenimv);
    						$em->persist($traslado);

    						$em->flush();
    						$this->get('session')->setFlash('ok', 'El traslado ha sido creada éxitosamente.');
    						return $this->redirect($this->generateUrl('traslado_list'));

    					}else{
    						$cant_almacenimv = $almacenimv->getCant();/*traigo cantidad total del almacen*/

    						$imvfarmacia = new ImvFarmacia();
    						$traslado->setImv($imv);
    						$traslado->setAlmacen($almacen);
    						$imvfarmacia->setImv($imv);
    						$imvfarmacia->setFarmacia($farmacia);
    						$imvfarmacia->setCant($cant_traslado);
    						$almacenimv->setCant($cant_almacenimv + $cant_traslado);

    						$em->persist($imvfarmacia);
    						$em->persist($almacenimv);

    						$em->persist($traslado);



    					}
          					$em->flush();

    					$this->get('session')->setFlash('ok', 'El traslado ha sido creada éxitosamente.');
    					return $this->redirect($this->generateUrl('traslado_show', array("traslado" => $traslado->getId())));
     					}
    				}

    		}
    	}

    	return $this->render('FarmaciaBundle:Traslado:new.html.twig', array(
    			'imv'    => $imv,
       			'form'   => $form->createView()
    	));
    }

    public function showAction($traslado)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->find($traslado);

    	$imv = $traslado->getImv();

    	$nombre_imv = $imv->getNombre();
    	$farmacia = $traslado->getFarmacia();

    	if (!$traslado) {
    		throw $this->createNotFoundException('El traslado solicitado no esta disponible.');
    	}


    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem($nombre_imv, $this->get("router")->generate('traslado_show', array('traslado' => $traslado->getId())));
    	$breadcrumbs->addItem($farmacia->getNombre());

    	return $this->render('FarmaciaBundle:Traslado:show.html.twig', array(
    			'trasfarma'  => $traslado,


    	));
    }

    public function editAction($traslado)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->find($traslado);

   	   if (!$traslado) {
    		throw $this->createNotFoundException('El traslado solicitado no esta disponible.');
    	}

    	$imv = $traslado->getImv();

    	$nombre_imv = $imv->getNombre();
    	$farmacia = $traslado->getFarmacia();

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Modificar");
    	$breadcrumbs->addItem($nombre_imv, $this->get("router")->generate('traslado_show', array('traslado' => $traslado->getId())));

    	$form   = $this->createForm(new TrasladoType(), $traslado);
    	$imvf = $form["imv"]->setData($nombre_imv);

    	return $this->render('FarmaciaBundle:Traslado:edit.html.twig', array(
    			'trasfarma' => $traslado,
    			'form' => $form->createView(),
    	));
    }


    public function updateAction($traslado)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->find($traslado);

        if (!$traslado) {
    		throw $this->createNotFoundException('El traslado solicitado no esta disponible.');
    	}

    	$form = $this->createForm(new TrasladoType(), $traslado);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {

    		$form->bind($request);

    		if ($form->isValid()) {
    			$tipo_traslado = $traslado->getTipo();/*tipo de traslado*/
    			$cant_traslado = $traslado->getCant();/*cantidad de traslado*/
    			//die(var_dump($cant_traslado));
    			$imv = $traslado->getImv();/*Entidad imv para llegar a la cantidad total*/
    			$cant_imv = $imv->getCantT();/*traigo cantidad total del imv*/
    			$farmacia = $traslado->getFarmacia();

    			$em = $this->getDoctrine()->getEntityManager();


    		if ($tipo_traslado=='T'){
    				if ($cant_imv < $cant_traslado){

    				$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia-'.$cant_imv = $imv->getCantT().'');
    		        return $this->redirect($this->generateUrl('traslado_edit', array('inventario' => $inventario->getId(), 'farmacia' => $farmacia->getId())));



    				}else {
    				$em->persist($traslado);
    				$em->flush();

    				$this->get('session')->setFlash('ok', 'El traslado ha sido modificado éxitosamente.');

    				return $this->redirect($this->generateUrl('traslado_show', array('traslado' => $traslado->getId())));
    				}
    			}
    			elseif (($tipo_traslado=='D')){

    				if ($cant_imv < $cant_traslado){

    					$this->get('session')->setFlash('error','La cantidad ingresada es mayor que cantidad en existencia,cantidad en existencia-'.$cant_imv = $imv->getCantT().'');
    					return $this->redirect($this->generateUrl('traslado_edit', array('traslado' => $traslado->getId())));



    				}else {
    				$em->persist($traslado);
    				$em->flush();

    				$this->get('session')->setFlash('ok', 'El traslado ha sido modificado éxitosamente.');

    				return $this->redirect($this->generateUrl('traslado_show', array('inventario' => $inventario->getId(), 'farmacia' => $farmacia->getId())));
    				}

    			}


    		}
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem($traslado->getId(), $this->get("router")->generate("traslado_show", array("traslado" => $traslado->getId())));
    	$breadcrumbs->addItem("Modificar".$traslado->getId());

    	return $this->render('FarmaciaBundle:Traslado:new.html.twig', array(
       			'traslado' => $traslado,
    			'form' => $form->createView(),
    	));
    }

    public function printAction()
    {


    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Traslados", $this->get("router")->generate("traslado_list"));
    	$breadcrumbs->addItem("Resultado Busqueda");



    	$em = $this->getDoctrine()->getEntityManager();
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findAll();
    	$request = $this->get('request');
    	$fecha_inicio = $request->request->get('fecha_inicio');
    	$fecha_fin = $request->request->get('fecha_fin');
    	//die(print_r($fecha_inicio));
    	$form   = $this->createForm(new TrasladoSearchType());


    	if(trim($fecha_inicio)){
    		$desde = explode('/',$fecha_inicio);

    		//die(print_r($desde));

    	if(!checkdate($desde[1],$desde[0],$desde[2])){
				$this->get('session')->setFlash('info', 'La fecha de inicio ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Traslado:searchprint.html.twig', array(
				'form'   => $form->createView()

			));

			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de inicio no puede estar en blanco.');
			return $this->render('FarmaciaBundle:Traslado:searchprint.html.twig', array(
				'form'   => $form->createView()

			));
		}

		if(trim($fecha_fin)){
			$hasta = explode('/',$fecha_fin);

			if(!checkdate($hasta[1],$hasta[0],$hasta[2])){
				$this->get('session')->setFlash('info', 'La fecha de finalización ingresada es incorrecta.');
				return $this->render('FarmaciaBundle:Traslado:searchprint.html.twig', array(
				'form'   => $form->createView()

			));
			}
		}else{
			$this->get('session')->setFlash('info', 'La fecha de finalización no puede estar en blanco.');
				return $this->render('FarmaciaBundle:Traslado:searchprint.html.twig', array(
				'form'   => $form->createView()

			));
		}

    	$query = "SELECT f FROM FarmaciaBundle:Traslado f WHERE
    				f.fecha >= :inicio AND
			    	f.fecha <= :fin
    				ORDER BY
    				f.fecha ASC";

    	$dql = $em->createQuery($query);



    	//die(print_r($dql));

    	$dql->setParameter('inicio', $desde[2]."/".$desde[1]."/".$desde[0].' 00:00:00');
    	$dql->setParameter('fin', $hasta[2]."/".$hasta[1]."/".$hasta[0].' 23:59:00');

    	$traslado = $dql->getResult();
    	//die(var_dump($ingreso));
    	//die("paso");

    	if(!$traslado)
    	{
    		$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');

    		return $this->redirect($this->generateUrl('traslado_searchprint'));
    	}

    	$pdf = $this->get('white_october.tcpdf')->create();
    	


    	$html = $this->renderView('FarmaciaBundle:Traslado:listado.html.twig',array(
    			'trasfarma'  => $traslado,
    			'fechai' =>$fecha_inicio,
    			'fechaf' =>$fecha_fin

    	));

        return $pdf->quick_pdf($html, 'Detallado_traslados_', 'D');          

    }

}