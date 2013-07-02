<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\CargoPyp;
use knx\ParametrizarBundle\Form\CargoPypType;

class CargoPypController extends Controller
{
	public function newAction($pyp)
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
    	$breadcrumbs->addItem("Asociar actividad");    	
    	 
    	$cargo_pyp = new CargoPyp();
    	$form   = $this->createForm(new CargoPypType(), $cargo_pyp);
    
    	return $this->render('ParametrizarBundle:CargoPyp:new.html.twig', array(
    			'pyp' => $pyp,
    			'form' => $form->createView()
    	));
    }

    public function saveAction($pyp)
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
    	$breadcrumbs->addItem("Asociar actividad");
    	 
    	$request = $this->getRequest();
    	 
    	$cargo_pyp = new CargoPyp();
    	$form = $this->createForm(new CargoPypType(), $cargo_pyp);
    	 
    	if ($request->getMethod() == 'POST') {
    
    		$form->bind($request);
    
    		if ($form->isValid()) {
    			
    			$existe_cargo_pyp = $em->getRepository('ParametrizarBundle:CargoPyp')->findBy(array('pyp' => $pyp->getId(), 'cargo' => $cargo_pyp->getCargo()->getId()));
    			
    			if(!$existe_cargo_pyp){
    				$cargo_pyp->setPyp($pyp);
    				
    				$em->persist($cargo_pyp);
    				$em->flush();
    				
    				$this->get('session')->setFlash('ok', 'El cargo ha sido asociado éxitosamente.');
    				
    				return $this->redirect($this->generateUrl('pyp_show', array("pyp" => $pyp->getId())));
    			}else{
    				$this->get('session')->setFlash('info', 'El cargo ya ha sido asociado anteriormente.');
    				
		    		return $this->render('ParametrizarBundle:CargoPyp:new.html.twig', array(
		    				'pyp' => $pyp,
		    				'form' => $form->createView()
		    		));
    			}
    		}
    	}
    
    	return $this->render('ParametrizarBundle:Pyp:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }

    public function showAction($pyp, $cargo)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$cargo_pyp = $em->getRepository('ParametrizarBundle:CargoPyp')->findOneBy(array("pyp" => $pyp, "cargo" => $cargo));

    	if (!$cargo_pyp) {
    		throw $this->createNotFoundException('La actividad categorizada no esta disponible.');
    	}
    	
    	$pyp = $cargo_pyp->getPyp();
    	$cargo = $cargo_pyp->getCargo();

    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem($pyp->getNombre(), $this->get("router")->generate("pyp_show", array("pyp" => $pyp->getId())));
    	$breadcrumbs->addItem($cargo->getNombre());

    	return $this->render('ParametrizarBundle:CargoPyp:show.html.twig', array(
    			'cp' => $cargo_pyp
    	));
    }

    public function editAction($pyp, $cargo)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$cargo_pyp = $em->getRepository('ParametrizarBundle:CargoPyp')->findOneBy(array("pyp" => $pyp, "cargo" => $cargo));

    	if (!$cargo_pyp) {
    		throw $this->createNotFoundException('La actividad categorizada no esta disponible.');
    	}
    	
    	$pyp = $cargo_pyp->getPyp();
    	$cargo = $cargo_pyp->getCargo();
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem($pyp->getNombre(), $this->get("router")->generate("pyp_show", array("pyp" => $pyp->getId())));
    	$breadcrumbs->addItem($cargo->getNombre(), $this->get("router")->generate("cargo_pyp_show", array("pyp" => $pyp->getId(), 'cargo' => $cargo->getId())));
    	$breadcrumbs->addItem("Modificar");
    
    	$form = $this->createForm(new CargoPypType(), $cargo_pyp);
    
    	return $this->render('ParametrizarBundle:CargoPyp:edit.html.twig', array(
    			'cp' => $cargo_pyp,
    			'form' => $form->createView()
    	));
    }
    
    
    public function updateAction($pyp, $cargo)
    {    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$cargo_pyp = $em->getRepository('ParametrizarBundle:CargoPyp')->findOneBy(array("pyp" => $pyp, "cargo" => $cargo));

    	if (!$cargo_pyp) {
    		throw $this->createNotFoundException('La actividad categorizada no esta disponible.');
    	}
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Categoría pyp", $this->get("router")->generate("pyp_list"));
    	$breadcrumbs->addItem("Cargo");
    
    	$form = $this->createForm(new CargoPypType(), $cargo_pyp);
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    
	    	if ($form->isValid()) {
	    		 
	    		$em->persist($cargo_pyp);
	    		$em->flush();
	    		
	    		$this->get('session')->setFlash('ok', 'La actividad ha sido modificada éxitosamente.');
	    		
	    		return $this->redirect($this->generateUrl('cargo_pyp_edit', array('pyp' => $cargo_pyp->getPyp()->getId(), 'cargo' => $cargo_pyp->getCargo()->getId())));
	    	}
    	}
    
    	return $this->render('ParametrizarBundle:CargoPyp:edit.html.twig', array(
    			'cp' => $cargo_pyp,
    			'form' => $form->createView()
    	));
    }
}