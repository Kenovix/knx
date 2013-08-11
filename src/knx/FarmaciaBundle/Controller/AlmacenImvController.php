<?php

namespace knx\FarmaciaBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\AlmacenImv;
use knx\FarmaciaBundle\Form\SearchAlmacenType;
use knx\ParametrizarBundle\Entity\Almacen;




class AlmacenImvController extends Controller
{


   public function searchAction()
    {
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


    public function listAction()
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

    		return $this->render('FarmaciaBundle:AlmacenImv:list.html.twig', array(
    				'almacenimv' => $almacenimv,
    				'form'   => $form->createView()
    		));
    	}else{
    		$this->get('session')->setFlash('error', 'Los parametros de busqueda ingresados son incorrectos.');

    		return $this->redirect($this->generateUrl('almacenimv_search'));
    	}
    }




}