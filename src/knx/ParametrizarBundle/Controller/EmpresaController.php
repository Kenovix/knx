<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\Empresa;
use knx\ParametrizarBundle\Form\EmpresaType;

class EmpresaController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Empresa", $this->get("router")->generate("empresa_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $empresa = $em->getRepository('ParametrizarBundle:Empresa')->findAll();
        
        return $this->render('ParametrizarBundle:Empresa:list.html.twig', array(
                'empresas'  => $empresa
        ));
    }
    
    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Empresa", $this->get("router")->generate("empresa_list"));
    	$breadcrumbs->addItem("Nueva");
    	
    	$empresa = new Empresa();
    	$form   = $this->createForm(new EmpresaType(), $empresa);
    	
    	$em = $this->getDoctrine()->getEntityManager();

    	return $this->render('ParametrizarBundle:Empresa:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Empresa", $this->get("router")->generate("empresa_list"));
    	$breadcrumbs->addItem("Nueva");
    	
    	$request = $this->getRequest();
    	
    	$empresa = new Empresa();    	
    	$form = $this->createForm(new EmpresaType(), $empresa);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {	    		
	    		 
	    		$em = $this->getDoctrine()->getEntityManager();
	    		
	    		$em->persist($empresa);
	    		$em->flush();
	    
	    		$this->get('session')->setFlash('ok', 'La empresa ha sido creada éxitosamente.');
	    
	    		return $this->redirect($this->generateUrl('empresa_show', array("empresa" => $empresa->getId())));
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:Empresa:new.html.twig', array(
    			'form'   => $form->createView()
    	));    
    }
    
    public function showAction($empresa)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find($empresa);
    	
    	if (!$empresa) {
    		throw $this->createNotFoundException('La empresa solicitada no esta disponible.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Empresa", $this->get("router")->generate("empresa_list"));
    	$breadcrumbs->addItem($empresa->getNombre());
    	
    	return $this->render('ParametrizarBundle:Empresa:show.html.twig', array(
    			'empresa'  => $empresa
    	));
    }
    
    public function editAction($empresa)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find($empresa);
    
    	if (!$empresa) {
    		throw $this->createNotFoundException('La empresa solicitada no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Empresa", $this->get("router")->generate("empresa_list"));
    	$breadcrumbs->addItem($empresa->getNombre(), $this->get("router")->generate("empresa_show", array("empresa" => $empresa->getId())));
    	$breadcrumbs->addItem("Modificar ".$empresa->getNombre());
    
    	$form = $this->createForm(new EmpresaType(), $empresa);
    
    	return $this->render('ParametrizarBundle:Empresa:edit.html.twig', array(
    			'empresa' => $empresa,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($empresa)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$empresa = $em->getRepository('ParametrizarBundle:Empresa')->find($empresa);
    
    	if (!$empresa) {
    		throw $this->createNotFoundException('La empresa solicitada no existe.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Empresa", $this->get("router")->generate("empresa_list"));
    	$breadcrumbs->addItem($empresa->getNombre(), $this->get("router")->generate("empresa_show", array("empresa" => $empresa->getId())));
    	$breadcrumbs->addItem("Modificar ".$empresa->getNombre());
    
    	$form = $this->createForm(new EmpresaType(), $empresa);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($empresa);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La empresa ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('empresa_edit', array('empresa' => $empresa->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:Empresa:edit.html.twig', array(
    			'empresa' => $empresa,
    			'form' => $form->createView()
    	));
    }
}