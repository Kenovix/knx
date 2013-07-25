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
    	$breadcrumbs->addItem("Stock_Pyp", $this->get("router")->generate("imvpyp_search"));
    	$breadcrumbs->addItem("Listado Stock_Pyp");
    	
    	
   		$form   = $this->createForm(new SearchPypType());
    	$request = $this->getRequest();
    	$form->bindRequest($request);
    
    	
    	   
    	$categoria = $form->get('categoria')->getData();
    	//die(var_dump($categoria));
    	if((( trim($categoria)))){
    	    
    		$em = $this->getDoctrine()->getEntityManager();
    		$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findOneBy(array('pyp' => $categoria));
    		//die(var_dump($imvpyp));
    		
    		
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
    				'pimv' => $imvpyp,    				
    				'form'   => $form->createView()
    		));
    	}else{
    			
    		return $this->redirect($this->generateUrl('imvpyp_search'));
    	}
    }
    
    
    
    public function newAction()
    {
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Listado_Stock", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem("Nueva Stock_Pyp");
    	
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
    	$breadcrumbs->addItem("Listado_Stock", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem("Nueva Stock_Pyp");
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findAll();
    	$imvpyp = new ImvPyp();
    	 
    	$request = $this->getRequest();
    	
    	$form   = $this->createForm(new ImvPypType(), $imvpyp);
    	if ($request->getMethod() == 'POST') {
    		
    	   		 
    		$form->bind($request);
    		 
    		if ($form->isValid()) {
    			$imv = $imvpyp->getImv();
    			$pyp = $imvpyp->getPyp();
    			$edadI = $imvpyp->getEdadIni();
    			$edadF = $imvpyp->getEdadFin();
    			
    			if ($edadF < $edadI){
    				
    				$this->get('session')->setFlash('info', 'Edad final es menor a Edad inicial');
    				
    				return $this->render('FarmaciaBundle:ImvPyp:new.html.twig', array(
    						'form'   => $form->createView()
    				));
    				
    			}
    			
                else {
    			$creada_imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findBy(array('pyp' => $pyp->getId(), 'imv' => $imv->getId()));
    			if(!$creada_imvpyp){    			
    			
    				$em = $this->getDoctrine()->getEntityManager();
    				 
    				$em->persist($imvpyp);
    				$em->flush();
    			
    				$this->get('session')->setFlash('ok', 'El imvpyp ha sido creada éxitosamente.');
    			
    				return $this->redirect($this->generateUrl('imvpyp_show', array('pyp' => $pyp->getId(), 'imv' => $imv->getId())));
    			}else{
    				$this->get('session')->setFlash('info', 'El item ya ha sido asociado anteriormente.');
    				
    				return $this->render('FarmaciaBundle:ImvPyp:new.html.twig', array(
    						'form'   => $form->createView()
    				));
    				
    				}
    				
    				
    			}		
   			}
    	}

    	return $this->render('FarmaciaBundle:ImvPyp:new.html.twig', array(
    	
    			'form' => $form->createView()
    	));
    }				
    			
  
    
    public function showAction($imv,$pyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findOneBy(array("pyp" => $pyp, "imv" => $imv));
    	//die(var_dump($imvpyp));
    	   
    	$pyp = $imvpyp->getPyp();
    	$imv = $imvpyp->getImv();
    	 
    	if (!$imvpyp) {
    		throw $this->createNotFoundException('El imvpyp solicitado no esta disponible.');
    	}
    	
    	//$inventario = $em->getRepository('FarmaciaBundle:Inventario')->findByImvPyp($imvpyp);
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Listado_Stock", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem($pyp->getNombre(), $this->get("router")->generate('imvpyp_show', array('pyp' => $pyp->getId(), 'imv' => $imv->getId())));
    	$breadcrumbs->addItem($imv->getNombre());
    	
    	return $this->render('FarmaciaBundle:ImvPyp:show.html.twig', array(
    			'pimv'  => $imvpyp
    			
    	));
    }
    
    public function editAction($imv,$pyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();    
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findOneBy(array("pyp" => $pyp, "imv" => $imv));
    	    
   	   if (!$imvpyp) {
    		throw $this->createNotFoundException('El imvpyp solicitado no esta disponible.');
    	}
    	
    	$pyp = $imvpyp->getPyp();
    	$imv = $imvpyp->getImv();
    	 
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia");
    	$breadcrumbs->addItem("Listado_Stock", $this->get("router")->generate("imvpyp_list"));
    	$breadcrumbs->addItem($pyp->getNombre(), $this->get("router")->generate('imvpyp_show', array('pyp' => $pyp->getId(), 'imv' => $imv->getId())));
    	$breadcrumbs->addItem($imv->getNombre());
    	$breadcrumbs->addItem("Modificar");
    
    	$form   = $this->createForm(new ImvPypType(), $imvpyp);
    
    	return $this->render('FarmaciaBundle:ImvPyp:edit.html.twig', array(
    			'pimv' => $imvpyp,
    			'form' => $form->createView(),
    	));
    }
    
    
    public function updateAction($imv,$pyp)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findOneBy(array("pyp" => $pyp, "imv" => $imv));
    	//die(var_dump($imvpyp));
    	   
        if (!$imvpyp) {
    		throw $this->createNotFoundException('El imvpyp solicitado no esta disponible.');
    	}
    
    	$form = $this->createForm(new ImvPypType(), $imvpyp);
    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		
    		$form->bind($request);
    		
	    	if ($form->isValid()) {
	    		$imv = $imvpyp->getImv();
	    		$pyp = $imvpyp->getPyp();
	    		//die(var_dump($imv));
	    		$edadI = $imvpyp->getEdadIni();
	    		$edadF = $imvpyp->getEdadFin();
	    		if ($edadF < $edadI){
	    		
	    			$this->get('session')->setFlash('info', 'Edad final es menor a Edad inicial');
	    		
	    			return $this->render('FarmaciaBundle:ImvPyp:edit.html.twig', array(
    					'pimv' => $imvpyp,
    					'form' => $form->createView(),
    				));
	    		
	    		}
	    		else{
	    		
	    		$em->persist($imvpyp);
	    		$em->flush();
	    		
    			
      			$this->get('session')->setFlash('ok', 'El stock_pyp ha sido modificado éxitosamente.');
    
    				return $this->redirect($this->generateUrl('imvpyp_show', array('pyp' => $pyp->getId(), 'imv' => $imv->getId())));
      	   		}
	    	}
    	}
    
    	$breadcrumbs = $this->get("white_october_breadcrumbs");
    	$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	$breadcrumbs->addItem("Farmacia", $this->get("router")->generate("imvpyp_list"));
    	//$breadcrumbs->addItem($imvpyp->getId(), $this->get("router")->generate("imvpyp_show", array("imvpyp" => $imvpyp->getId())));
    	//$breadcrumbs->addItem("Modificar".$imvpyp->getId());
    
    	return $this->render('FarmaciaBundle:ImvPyp:new.html.twig', array(
       			'pimv' => $imvpyp,
    			'form' => $form->createView(),
    	));
    }
    
    
    
    public function deleteAction($imv,$pyp)
    {
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$imvpyp = $em->getRepository('FarmaciaBundle:ImvPyp')->findOneBy(array("pyp" => $pyp, "imv" => $imv));
    	    
    	 
    	  	 
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
    
    
    
    	    
    
    