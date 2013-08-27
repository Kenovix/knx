<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Imv;
use knx\FarmaciaBundle\Entity\Ingreso;
use knx\FarmaciaBundle\Entity\Traslado;
use knx\FarmaciaBundle\Entity\Devolucion;
use knx\FarmaciaBundle\Entity\AlmacenImv;
use knx\FarmaciaBundle\Form\UpdateInventarioType;
use knx\FarmaciaBundle\Form\InventarioType;



class InventarioController extends Controller
{
	public function listAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("inventario_list"));
    	$breadcrumbs->addItem("Listado");

    	$em = $this->getDoctrine()->getEntityManager();
        $inventario = $em->getRepository('FarmaciaBundle:Inventario')->findAll();

        return $this->render('FarmaciaBundle:Inventario:list.html.twig', array(
                'Inventario'  => $inventario
        ));
    }

    public function newAction($ingreso)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("inventario_list"));
    	$breadcrumbs->addItem("Nueva Inventario");

    	$em = $this->getDoctrine()->getEntityManager();
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->findAll();

    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);

    	if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}

    	$inventario = new Inventario();


    	$form   = $this->createForm(new InventarioType(), $inventario);

    	//$imv = new Imv();

    	//die(var_dump($precio_venta));
    	//$precio_venta = $imv->getprecioVenta();
    	//$precioventa = $form["precioventa"]->setData($precio_venta);

    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
    			'ingreso' => $ingreso,
    			'form'   => $form->createView()
    	));
    }


    public function saveAction($ingreso)
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");

    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem("Nueva Inventario");

    	$request = $this->getRequest();
    	$inventario  = new Inventario();

    	$form    = $this->createForm(new InventarioType(), $inventario);

    	$em = $this->getDoctrine()->getEntityManager();
        $ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);


    if ($request->getMethod() == 'POST') {

    		$form->bind($request);

	    	if ($form->isValid()) {

	    		$existe_inventario = $em->getRepository('FarmaciaBundle:Inventario')->findBy(array('ingreso' => $ingreso->getId(), 'imv' => $inventario->getImv()->getId()));

	    		if(!$existe_inventario){


	    		$imv = $inventario->getImv(); /*Entidad Imv*/
                $almacen = $ingreso->getAlmacen();
	    		$cantidad_actual = $imv->getCantT();
	    		$cantidad_ingresada = $inventario->getCant();
	    		$precio_compra = $inventario->getPrecioCompra();
	    		$precioventa = $form->get('precioventa')->getData();


//die (var_dump($almacenid));

	    		if($precioventa<$precio_compra){

	    			$this->get('session')->setFlash('error', 'El precio de venta no puede ser menor a precio de compra.');

	    			return $this->redirect($this->generateUrl('inventario_new', array("ingreso" => $ingreso->getId())));

	    		}else{


	    		$imv->setCantT($cantidad_actual+$cantidad_ingresada);
	    		$imv->setPrecioVenta($precioventa);

	    		$inventario->setPrecioTotal($cantidad_ingresada * $precio_compra);
	    		$inventario->setIngreso($ingreso);

	    		$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findoneBy(array('almacen'=> $ingreso->getAlmacen(), 'imv' => $inventario->getImv()->getId()));

	    		if($almacenimv){

	    			$cantidad_ingresada = $inventario->getCant();
					$cantidad_almacenimv = $almacenimv->getCant();
					//die (var_dump($cantidad_almacenimv));

	    			$almacenimv->setCant($cantidad_ingresada + $cantidad_almacenimv);

	    			$em->persist($almacenimv);


	    		}else{

	    			$almacenimv = new AlmacenImv();
	    			$almacenimv->setAlmacen($ingreso->getAlmacen());
	    			$almacenimv->setImv($imv);
	    			$cantidad_actual = $imv->getCantT();
	    			$almacenimv->setCant($cantidad_ingresada);
	    			$em->persist($almacenimv);

	    		}



	    		$em->persist($inventario);
	    		$em->persist($imv);

	    		$em->flush();

	    		$this->get('session')->setFlash('ok', 'El Invenatrio ha sido creado éxitosamente.');

	    		}return $this->redirect($this->generateUrl('ingreso_show', array("ingreso" => $ingreso->getId())));
	    	}else{
    				$this->get('session')->setFlash('info', 'El cargo ya ha sido asociado anteriormente.');

		    		return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
		    				'ingreso' => $ingreso,
		    				'form' => $form->createView()
		    		));
    			}
    	}
    }
    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }


    public function showAction($ingreso,$imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->find($ingreso);

    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findOneBy(array("ingreso" => $ingreso, "imv" => $imv));

    	if (!$inventario) {
    		throw $this->createNotFoundException('El inventario solicitada no esta disponible.');
    	}

    	$ingreso = $inventario->getIngreso();
    	$imv = $inventario->getImv();

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($ingreso->getNumFact(), $this->get("router")->generate("ingreso_show", array("ingreso" => $ingreso->getId())));
    	$breadcrumbs->addItem($imv->getNombre());


    	return $this->render('FarmaciaBundle:Inventario:show.html.twig', array(
    			'ingreso'=> $ingreso,
    			'inv'  => $inventario
    	));
    }

    public function editAction($ingreso,$imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findOneBy(array("ingreso" => $ingreso, "imv" => $imv));
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findOneBy(array("imv" => $imv));
    	$devolucion = $em->getRepository('FarmaciaBundle:Devolucion')->findOneBy(array("imv" => $imv));

    	//die(var_dump($inventario));
    	$imv = $inventario->getImv();
    	$precio_venta = $imv->getprecioVenta();
    	//die(var_dump($precio_venta));

    	$form   = $this->createForm(new UpdateInventarioType(), $inventario);
    	$precioventa = $form["precioventa"]->setData($precio_venta);


   	   if (!$inventario) {
    		throw $this->createNotFoundException('El inventario solicitada no esta disponible.');
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	//$breadcrumbs->addItem($inventario->getId(), $this->get("router")->generate("inventario_edit", array("inventario" => $inventario->getId())));
    	//$breadcrumbs->addItem("Modificar".$inventario->getId());







    	return $this->render('FarmaciaBundle:Inventario:edit.html.twig', array(
    			'inv' => $inventario,
    			'form' => $form->createView(),
    	));

    }



    public function updateAction($ingreso,$imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findOneBy(array("ingreso" => $ingreso, "imv" => $imv));

        if (!$inventario) {
    		throw $this->createNotFoundException('La inventario solicitada no esta disponible.');
    	}

    	$form = $this->createForm(new UpdateInventarioType(), $inventario);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {

    	$cantidad_inventario = $inventario->getcant();
    		//die(var_dump($imv));
    	$precio_compra = $inventario->getPrecioCompra();
    	$imv = $inventario->getImv();




    		$form->bind($request);


    		if ($form->isValid()) {

    			$em = $this->getDoctrine()->getEntityManager();
    			$imv = $inventario->getImv();
    			$cantidad_actual = $imv->getCantT();
    			$cantidad_ingresada = $inventario->getCant();

    			//die(var_dump($medicamento_ingresado));
    			$precio_compra = $inventario->getPrecioCompra();
    			$ingreso = $inventario->getIngreso();
    			$imv = $inventario->getImv();
    			$precioventa = $form->get('precioventa')->getData();
    			//die(var_dump($precioventa));




    			if($precioventa<$precio_compra or $precioventa==$precio_compra ){

    				$this->get('session')->setFlash('error', 'El precio de venta no puede ser menor o igual a precio de compra.');

    				return $this->redirect($this->generateUrl('inventario_edit', array("ingreso" => $ingreso->getId(), "imv" => $imv->getId())));

    			}else{



    			$inventario->setPrecioTotal($cantidad_ingresada * $precio_compra);


    			if ($cantidad_ingresada < $cantidad_inventario){

    				$cantidad_diferencia =  $cantidad_inventario - $cantidad_ingresada;



    			}else {
    				$cantidad_diferencia =  $cantidad_ingresada - $cantidad_inventario;
    			}


    			if ($cantidad_ingresada < $cantidad_inventario){

    				$imv->setCantT($cantidad_actual-$cantidad_diferencia);



    			}else {
    				$imv->setCantT($cantidad_actual+$cantidad_diferencia);
    			}



    			$imv->setPrecioVenta($precioventa);

	    		$em->persist($inventario);
	    		$em->persist($imv);

	    		$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findoneBy(array('almacen'=> $ingreso->getAlmacen(), 'imv' => $inventario->getImv()->getId()));

	    		if($almacenimv){

	    			$imv = $inventario->getImv();
    				$cantidad_actual = $almacenimv->getCant();

	    			if ($cantidad_ingresada < $cantidad_inventario){

	    				$cantidad_diferencia =  $cantidad_inventario - $cantidad_ingresada;



	    			}else {
	    				$cantidad_diferencia =  $cantidad_ingresada - $cantidad_inventario;
	    			}


	    			if ($cantidad_ingresada < $cantidad_inventario){

	    				$almacenimv->setCant($cantidad_actual-$cantidad_diferencia);



	    			}else {
	    				$almacenimv->setCant($cantidad_actual+$cantidad_diferencia);
	    			}

	    			//$almacenimv->setCant($cantidad_actual);
	    			$em->persist($almacenimv);


	    		}
	    		$em->flush();

    			$this->get('session')->setFlash('ok', 'La inventario ha sido creada éxitosamente.');

    			return $this->redirect($this->generateUrl('inventario_edit', array("ingreso" => $ingreso->getId(), "imv" => $imv->getId())));
    		}

    		return $this->render('FarmaciaBundle:Inventario:edit.html.twig', array(
    				'inventario' => $inventario,
    				'form'   => $form->createView()
    		));
    	}

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($inventario->getId(), $this->get("router")->generate("inventario_edit", array("Inventario" => $inventario->getId())));
    	$breadcrumbs->addItem("Modificar".$inventario->getId());

    	return $this->render('FarmaciaBundle:Inventario:new.html.twig', array(
       			'inventario' => $inventario,
    			'form' => $form->createView(),
    	));
    	}
    }

public function deleteAction($ingreso,$imv)
    {

    	$em = $this->getDoctrine()->getEntityManager();

    	$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findOneBy(array("ingreso" => $ingreso, "imv" => $imv));
    	$traslado = $em->getRepository('FarmaciaBundle:Traslado')->findOneBy(array("imv" => $imv));


    	$imv = $inventario->getImv();
    	$cantidad_inventario = $inventario->getcant();
    	$ingreso = $inventario->getIngreso();

    	$cantidad_actual = $imv->getCantT();

    	$precio_compra = $inventario->getPrecioCompra();


    	//die(var_dump($cantidad_actual));
    	if (!$inventario) {
    		throw $this->createNotFoundException('El Inventario solicitado no existe.');
    	}
    	if ($traslado == null){

    		if ($cantidad_inventario < $cantidad_actual or $cantidad_inventario == $cantidad_actual){

    		$imv->setCantT($cantidad_actual-$cantidad_inventario);



    		$em->remove($inventario);
    		$em->flush();


    		$almacenimv = $em->getRepository('FarmaciaBundle:AlmacenImv')->findoneBy(array('almacen'=> $ingreso->getAlmacen(), 'imv' => $inventario->getImv()->getId()));
    		$cantidad_almacenimv = $almacenimv->getCant();
    		//die(var_dump($cantidad_almacenimv));

    	if($almacenimv){


    		if ($cantidad_inventario < $cantidad_almacenimv or $cantidad_inventario == $cantidad_almacenimv){

    			$almacenimv->setCant($cantidad_almacenimv-$cantidad_inventario);



    			$em->persist($almacenimv);
    			$em->flush();

    			$this->get('session')->setFlash('ok', 'El inventario ha sido eliminado.');

    			return $this->redirect($this->generateUrl('ingreso_list'));

    		}
    	  }
    	 }
       }
    	else {

    		$this->get('session')->setFlash('error','El Item no se puede eliminar ya que ha sido trasladado');
    		return $this->redirect($this->generateUrl('ingreso_list'));
    	}
      }
    }





