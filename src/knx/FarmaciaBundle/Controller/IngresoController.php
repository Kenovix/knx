<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Ingreso;
use knx\FarmaciaBundle\Entity\Inventario;
use knx\FarmaciaBundle\Entity\Imv;
use knx\FarmaciaBundle\Form\IngresoType;
use knx\FarmaciaBundle\Form\IngresoSearchType;


class IngresoController extends Controller
{
	
	public function searchAction(){
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Farmacia");
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
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $ingreso = $em->getRepository('FarmaciaBundle:Ingreso')->findAll(); 
        
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
    	   
    	
    	if(trim($fecha_inicio)){
    		$desde = explode('-',$fecha_inicio);
    		
    		//die(print_r($desde));
    		
    		if(!checkdate($desde[1],$desde[2],$desde[0])){
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
    		$hasta = explode('-',$fecha_fin);
    		
    		if(!checkdate($hasta[1],$hasta[2],$hasta[0])){
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
    	
        		$query = "SELECT f FROM FarmaciaBundle:Ingreso f WHERE 
    				f.fecha >= :inicio AND
			    	f.fecha <= :fin
    				ORDER BY
    				f.fecha ASC";
    		
    		$dql = $em->createQuery($query);    		 
    		
    	

    		//die(print_r($dql));
    		
    		$dql->setParameter('inicio', $desde[0]."-".$desde[1]."-".$desde[2].' 00:00:00');
    		$dql->setParameter('fin', $hasta[0]."-".$hasta[1]."-".$hasta[2].' 23:59:00');
    		
    		$ingreso = $dql->getResult();
    		//die(var_dump($ingreso));
    		//die("paso");
    		
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
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($ingreso);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El ingreso ha sido creada éxitosamente.');
    
    			return $this->redirect($this->generateUrl('ingreso_show', array("ingreso" => $ingreso->getId())));	
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
    	
    	
    	 
    	if (!$ingreso) {
    		throw $this->createNotFoundException('El ingreso solicitado no esta disponible.');
    	}
    	
    	$inventarios = $em->getRepository('FarmaciaBundle:Inventario')->findByIngreso($ingreso);
    	//$inventario = new Inventario();
    	
    	
    	//$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findByIngreso($ingreso);
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Ingresos", $this->get("router")->generate("ingreso_list"));
    	$breadcrumbs->addItem($ingreso->getnumFact());
    	 
    	return $this->render('FarmaciaBundle:Ingreso:show.html.twig', array(
    			'ingreso'  => $ingreso,
    			'inventarios' => $inventarios
    			
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
    
    
    
} 