<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\Pyp;
use knx\ParametrizarBundle\Form\PypType;
use knx\ParametrizarBundle\Entity\CargoPyp;
use knx\ParametrizarBundle\Form\CargoPypType;

class PypController extends Controller
{
	public function listAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $pyp = $em->getRepository('ParametrizarBundle:Pyp')->findAll();
        
        return $this->render('ParametrizarBundle:Pyp:list.html.twig', array(
                'pyps'  => $pyp
        ));
    }

    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem("Nuevo");
    	
    	$pyp = new Pyp();
    	$form   = $this->createForm(new PypType(), $pyp);
    	
    	$em = $this->getDoctrine()->getEntityManager();

    	return $this->render('ParametrizarBundle:Pyp:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	 
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem("Nueva");
    	
    	$request = $this->getRequest();
    	
    	$pyp = new Pyp();    	
    	$form = $this->createForm(new PypType(), $pyp);
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {	    		
	    		 
	    		$em = $this->getDoctrine()->getEntityManager();
	    		
	    		$em->persist($pyp);
	    		$em->flush();
	    
	    		$this->get('session')->setFlash('ok', 'La categoría ha sido creada éxitosamente.');
	    
	    		return $this->redirect($this->generateUrl('pyp_show', array("pyp" => $pyp->getId())));
	    	}
    	}
	        	
    	return $this->render('ParametrizarBundle:Pyp:new.html.twig', array(
    			'form'   => $form->createView()
    	));    
    }

    public function showAction($pyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$pyp = $em->getRepository('ParametrizarBundle:Pyp')->find($pyp);
    	
    	if (!$pyp) {
    		throw $this->createNotFoundException('El pyp solicitado no esta disponible.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem($pyp->getNombre());
    	
    	$cargo_pyp = $em->getRepository('ParametrizarBundle:CargoPyp')->findBy(array('pyp' => $pyp->getId()));
    	
    	return $this->render('ParametrizarBundle:Pyp:show.html.twig', array(
    			'pyp'  => $pyp,
    			'cargo_pyp' => $cargo_pyp
    	));
    }
    
    public function editAction($pyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$pyp = $em->getRepository('ParametrizarBundle:Pyp')->find($pyp);
    
    	if (!$pyp) {
    		throw $this->createNotFoundException('El pyp solicitado no existe');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem($pyp->getNombre(), $this->get("router")->generate("pyp_show", array("pyp" => $pyp->getId())));
    	$breadcrumbs->addItem("Modificar");
    
    	$form = $this->createForm(new PypType(), $pyp);
    
    	return $this->render('ParametrizarBundle:Pyp:edit.html.twig', array(
    			'pyp' => $pyp,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($pyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$pyp = $em->getRepository('ParametrizarBundle:Pyp')->find($pyp);
    
    	if (!$pyp) {
    		throw $this->createNotFoundException('La pyp solicitada no existe.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem($pyp->getNombre(), $this->get("router")->generate("pyp_show", array("pyp" => $pyp->getId())));
    	$breadcrumbs->addItem("Modificar");
    
    	$form = $this->createForm(new PypType(), $pyp);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($pyp);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La pyp ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('pyp_edit', array('pyp' => $pyp->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:Pyp:edit.html.twig', array(
    			'pyp' => $pyp,
    			'form' => $form->createView()
    	));
    }
}