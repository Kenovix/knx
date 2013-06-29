<?php

namespace knx\FarmaciaBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\FarmaciaBundle\Entity\ImvPyp;
use knx\FarmaciaBundle\Form\ImvPypType;
use knx\FarmaciaBundle\Form\SearchPypType;



class ImvPypController extends Controller
{
	
	public function searchAction()
	{
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Farmacia");		
		$breadcrumbs->addItem("Busqueda");
		
		$form   = $this->createForm(new SearchPypType());
	
		return $this->render('FarmaciaBundle:ImvPyp:search.html.twig', array(
				'form'   => $form->createView()
		));
	}	
	
	
	public function listAction()
    {   
    	
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("ImvPyp", $this->get("router")->generate("imvpyp_search"));
    	$breadcrumbs->addItem("Listado ImvPyp");
    	
    	
   		$form   = $this->createForm(new SearchPypType());
    	$request = $this->getRequest();
    	$form->bindRequest($request);
    
    	
    	   
    	$categoria = $form->get('categoria')->getData();
    	//die(var_dump($categoria));
    	if((( trim($categoria)))){
    	    
    		$em = $this->getDoctrine()->getEntityManager();
    		$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findOneBy(array('pyp' => $categoria));
    		//die(var_dump($imv));
    		
    		
    		$query = "SELECT i FROM FarmaciaBundle:ImvPyp i WHERE ";
    		$parametros = array();
    		
    		if($categoria){
    			$query .= "i.pyp = :categoria AND ";
    			$parametros["categoria"] = $categoria;
    		}
    	
    		 
    		
    		$query = substr($query, 0, strlen($query)-4);
    		 
    		 
    		$dql = $em->createQuery($query);
    		$dql->setParameters($parametros);
    		
    		$imvpyp = $dql->getResult();
    
    		if(!$imvpyp)
    		{
    			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');
    			 
    			return $this->redirect($this->generateUrl('imvpyp_search'));
    		}
    	 	
    		return $this->render('FarmaciaBundle:ImvPyp:list.html.twig', array(
    				'imvpyp' => $imvpyp,    				
    				'form'   => $form->createView()
    		));
    	}else{
    		$this->get('session')->setFlash('error', 'Los parametros de busqueda ingresados son incorrectos.');
    			
    		return $this->redirect($this->generateUrl('imvpyp_search'));
    	}
    }
    
    
    
    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("ImvPyps", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem("Nueva ImvPyp");
    	
    	$imvpyp = new ImvPyp();    	
    	$form   = $this->createForm(new ImvPypType(), $imvpyp);

    	return $this->render('FarmaciaBundle:ImvPyp:new.html.twig', array(
    			'form'   => $form->createView()
    	));
    }
    
    
    public function saveAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
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
    
    public function showAction($imvpyp)
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
    
    public function editAction($imvpyp)
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
    
    
    public function updateAction($imvpyp)
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
    
    
    
    public function deleteAction($imvpyp)
    {
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->find($imvpyp);
    
    	 
    	  	 
    	//die(var_dump($cantidad_actual));
    	if (!$imvpyp) {
    		throw $this->createNotFoundException('El Item Pyp solicitado no existe.');
    	}
    		$em->remove($imvpyp);
    		$em->flush();
    
    		$this->get('session')->setFlash('ok', 'El Item ha sido eliminado.');
    
    		return $this->redirect($this->generateUrl('imvpyp_list'));
    		 
    	 		 
    		
    		 
    	}
    	 
}    	 
    
    
    
    	    
    
    