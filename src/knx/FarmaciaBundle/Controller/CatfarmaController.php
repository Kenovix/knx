<?php

namespace knx\FarmaciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\CategoriaImv;
use knx\FarmaciaBundle\Entity\Farmacia;
use knx\FarmaciaBundle\Form\CategoriaImvType;
use knx\FarmaciaBundle\Form\FarmaciaType;

class CatfarmaController extends Controller
{
	public function catListAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("categoria_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $catImv = $em->getRepository('FarmaciaBundle:CategoriaImv')->findAll();
        
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
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	$farmacia = $em->getRepository('FarmaciaBundle:Farmacia')->findAll();
    
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
    
} 