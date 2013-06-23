<?php

namespace knx\FarmaciaBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\Imv;
use knx\FarmaciaBundle\Form\ImvType;


class ImvController extends Controller
{
	
public function ListAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Imvs", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $imv = $em->getRepository('FarmaciaBundle:Imv')->findAll();
        
        return $this->render('FarmaciaBundle:Imv:list.html.twig', array(
                'imv'  => $imv
        ));
    }
    
    public function NewAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Imvs", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem("Nueva Imv");
    	
    	$imv = new Imv();    	
    	$form   = $this->createForm(new ImvType(), $imv);

    	return $this->render('FarmaciaBundle:Imv:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    
    public function SaveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem("Nueva Imv");
    	 
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
    
    			$this->get('session')->setFlash('ok', 'El imv ha sido creada éxitosamente.');
    
    			return $this->redirect($this->generateUrl('imv_show', array("imv" => $imv->getId())));	
    		}
    	}
    	 
    	return $this->render('FarmaciaBundle:Imv:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }
    
    public function ShowAction($imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find($imv);
    	
    	
    	 
    	if (!$imv) {
    		throw $this->createNotFoundException('El imv solicitado no esta disponible.');
    	}
    	
    	//$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findByImv($imv);
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Imvs", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem($imv->getcodAdmin());
    	 
    	return $this->render('FarmaciaBundle:Imv:show.html.twig', array(
    			'imv'  => $imv
    			
    	));
    }
    
    public function EditAction($imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find($imv);
    
   	   if (!$imv) {
    		throw $this->createNotFoundException('El imv solicitado no esta disponible.');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Imvs", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem($imv->getcodAdmin(), $this->get("router")->generate("imv_show", array("imv" => $imv->getId())));
    	$breadcrumbs->addItem("Modificar".$imv->getcodAdmin());
    
    	$form   = $this->createForm(new ImvType(), $imv);
    
    	return $this->render('FarmaciaBundle:Imv:edit.html.twig', array(
    			'imv' => $imv,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function UpdateAction($imv)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imv = $em->getRepository('FarmaciaBundle:Imv')->find($imv);
    
        if (!$imv) {
    		throw $this->createNotFoundException('El imv solicitado no esta disponible.');
    	}
    
    	$form = $this->createForm(new ImvType(), $imv);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($imv);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El imv ha sido modificado éxitosamente.');
    
    			return $this->redirect($this->generateUrl('imv_show', array("imv" => $imv->getId())));	
    		}
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("imv_list"));
    	$breadcrumbs->addItem($imv->getgetcodAdmin(), $this->get("router")->generate("imv_show", array("imv" => $imv->getId())));
    	$breadcrumbs->addItem("Modificar".$imv->getcodAdmin());
    
    	return $this->render('FarmaciaBundle:Imv:new.html.twig', array(
       			'imv' => $imv,
    			'form' => $form->createView(),
    	));
    }
    
    
    
} 