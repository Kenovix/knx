<?php

namespace knx\FarmaciaBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\ImvPyp;
use knx\FarmaciaBundle\Form\ImvPypType;


class ImvPypController extends Controller
{
	
public function ListAction()
    {   
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("ImvPyps", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem("Listado");
    	
    	$em = $this->getDoctrine()->getEntityManager();    
        $imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findAll();
        
        return $this->render('FarmaciaBundle:ImvPyp:list.html.twig', array(
                'imvpyp'  => $imvpyp
        ));
    }
    
    public function NewAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("ImvPyps", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem("Nueva ImvPyp");
    	
    	$imvpyp = new ImvPyp();    	
    	$form   = $this->createForm(new ImvPypType(), $imvpyp);

    	return $this->render('FarmaciaBundle:ImvPyp:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    
    public function SaveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("farmacia_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem("Nueva ImvPyp");
    	 
    	$imvpyp = new ImvPyp();
    	 
    	$request = $this->getRequest();
    	$form   = $this->createForm(new ImvPypType(), $imvpyp);
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($imvpyp);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El imvpyp ha sido creada éxitosamente.');
    
    			return $this->redirect($this->generateUrl('imvpyp_show', array("imvpyp" => $imvpyp->getId())));	
    		}
    	}
    	 
    	return $this->render('FarmaciaBundle:ImvPyp:new.html.twig', array(
       			'form'   => $form->createView()
    	));
    }
    
    public function ShowAction($imvpyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->find($imvpyp);
    	
    	
    	 
    	if (!$imvpyp) {
    		throw $this->createNotFoundException('El imvpyp solicitado no esta disponible.');
    	}
    	
    	//$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findByImvPyp($imvpyp);
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("ImvPyps", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem($imvpyp->getId());
    	 
    	return $this->render('FarmaciaBundle:ImvPyp:show.html.twig', array(
    			'imvpyp'  => $imvpyp
    			
    	));
    }
    
    public function EditAction($imvpyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->find($imvpyp);
    
   	   if (!$imvpyp) {
    		throw $this->createNotFoundException('El imvpyp solicitado no esta disponible.');
    	}
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("ImvPyps", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem($imvpyp->getId(), $this->get("router")->generate("imvpyp_show", array("imvpyp" => $imvpyp->getId())));
    	$breadcrumbs->addItem("Modificar".$imvpyp->getId());
    
    	$form   = $this->createForm(new ImvPypType(), $imvpyp);
    
    	return $this->render('FarmaciaBundle:ImvPyp:edit.html.twig', array(
    			'imvpyp' => $imvpyp,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function UpdateAction($imvpyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->find($imvpyp);
    
        if (!$imvpyp) {
    		throw $this->createNotFoundException('El imvpyp solicitado no esta disponible.');
    	}
    
    	$form = $this->createForm(new ImvPypType(), $imvpyp);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    	
    			$em = $this->getDoctrine()->getEntityManager();
    	
    			$em->persist($imvpyp);
    			$em->flush();
    
    			$this->get('session')->setFlash('ok', 'El imvpyp ha sido modificado éxitosamente.');
    
    			return $this->redirect($this->generateUrl('imvpyp_show', array("imvpyp" => $imvpyp->getId())));	
    		}
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem($imvpyp->getId(), $this->get("router")->generate("imvpyp_show", array("imvpyp" => $imvpyp->getId())));
    	$breadcrumbs->addItem("Modificar".$imvpyp->getId());
    
    	return $this->render('FarmaciaBundle:ImvPyp:new.html.twig', array(
       			'imvpyp' => $imvpyp,
    			'form' => $form->createView(),
    	));
    }
    
    
    
} 