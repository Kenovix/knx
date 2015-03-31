<?php

namespace knx\FacturacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use knx\FacturacionBundle\Form\MotivoType;
use knx\FacturacionBundle\Form\FacturasType;
use knx\FacturacionBundle\Entity\Factura;
use knx\FacturacionBundle\Entity\FacturaCargo;
use knx\ParametrizarBundle\Entity\Cliente;
use knx\FacturacionBundle\Form\FacturascfType;
use knx\FacturacionBundle\Form\CambioCfType;
use knx\FacturacionBundle\Form\CambioProType;
use knx\UsuarioBundle\Entity\Usuario;





class FacturasController extends Controller
{
	public function searchAction()
                
        {
            
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	    $breadcrumbs->addItem("Buscar factura", $this->get("router")->generate("facturas_search"));
    	
            $form   = $this->createForm(new FacturasType());

            $em = $this->getDoctrine()->getEntityManager();
            /*$facturas = $em->getRepository('FacturacionBundle:Factura')->findAll();

            if (!$facturas) {
    		$this->get('session')->setFlash('info', 'Stock sin ingresos');
            }*/

            return $this->render('FacturacionBundle:Facturas:search.html.twig', array(
    			'form'   => $form->createView()
            ));
        }
	
	public function listAction()
	{
		$request = $this->getRequest();
		$form    = $this->createForm(new FacturasType());
		$form->bindRequest($request);
		
		if ($form->isValid()) 
		{
			$idfactura = $form->get('factura')->getData();
	 	
	 		if(((trim($idfactura) && is_numeric($idfactura)))){
	 	
	 		$em = $this->getDoctrine()->getEntityManager();
	 		$factura = $em->getRepository('FacturacionBundle:Factura')->findOneBy(array('id' => $idfactura));
            
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
            $breadcrumbs->addItem("Buscar factura", $this->get("router")->generate("facturas_search"));
            $breadcrumbs->addItem($idfactura);

	 		if(!$factura){
	 			$this->get('session')->setFlash('info', 'El número de factura no se encuentra.');
	 				
	 			return $this->redirect($this->generateUrl('facturas_search'));
	 		}
                        
				$est_fact = $factura->getEstado();

                if ($est_fact=='X'){
                            
                	$this->get('session')->setFlash('info', 'Factura ya Anulada.');
	 				
	 		     	return $this->redirect($this->generateUrl('facturas_search'));
                            
                }
	 			
	 		$dql = $em->createQuery("SELECT f
										FROM
											FacturacionBundle:Factura f 											
										WHERE 
											f.id = :id");	 			
                        	 			
	 		$dql->setParameter('id', $factura->getId());
	 		$dql->getSql();
	 		$facturas = $dql->getResult();
	 		
	 		if(!$facturas)
	 		{
	 			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');
	 			
				return $this->redirect($this->generateUrl('facturas_search'));
	 		}
	 			
	 		return $this->render('FacturacionBundle:Facturas:list.html.twig', array(
	 				'facturas1' => $facturas,
	 				
	 				'form'   => $form->createView()
	 		));	 			
	 	}else{
	 		$this->get('session')->setFlash('info', 'Los parametros de busqueda ingresados son incorrectos.');
	 			
	 		return $this->redirect($this->generateUrl('facturas_search'));
	 	}	 			
	}		
			                       

                
    }
    
    
    public function motivoAction($factura1)
                
        {
            $em = $this->getDoctrine()->getEntityManager();

            $facturas = $em->getRepository('FacturacionBundle:Factura')->find($factura1);
    	
            $fact_num = $facturas->getId();
            //$paciente = $facturas->getPaciente();
            //$id_paciente = $paciente->getIdentificacion();
           
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
            $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_search"));
            $breadcrumbs->addItem($fact_num,$this->get("router")->generate('facturas_search'));
            $breadcrumbs->addItem("Anular");

            $form   = $this->createForm(new MotivoType(), $facturas);
            //$fact = $form["factura"]->setData($fact_num);
            //$idpa = $form["idp"]->setData($id_paciente);
             //die(var_dump($fact));
            //$almacenf = $form["almacen"]->setData($nombre_almacen);

            return $this->render('FacturacionBundle:Facturas:motivo.html.twig', array(
                            'facturas' => $facturas,
                            'form'   => $form->createView()
            ));
    }
    
    
    
        public function AnularAction($factura1)

                {

                    $em = $this->getDoctrine()->getEntityManager();
                    $facturas = $em->getRepository('FacturacionBundle:Factura')->find($factura1);
                    $fact_cargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findoneBy(array('factura'=> $factura1));
                    $fact_imv = $em->getRepository('FacturacionBundle:FacturaImv')->findoneBy(array('factura'=> $factura1));
                    $request = $this->getRequest();
                    $est_fact = $facturas->getEstado();
                    $form = $this->createForm(new MotivoType(), $facturas);
                    $request = $this->getRequest();
                    $form->bind($request);
                    $motivo = $form->get('motivo')->getData();
                    $nfactura = $form->get('nfactura')->getData();

                    //die(var_dump($nfactura));
                    
                if ($facturas and $fact_cargo==NULL and $fact_imv) {
                                       
                        $facturas->setEstado('X');
                       // $fact_cargo->setEstado('X');
                        $fact_imv->setEstado('X');
                        $facturas->setMotivo($motivo);
                        $facturas->setNfactura($nfactura);    
                        $em->persist($facturas);
                        //$em->persist($fact_cargo);
                        $em->persist($fact_imv);
                        $em->flush();

    			$this->get('session')->setFlash('ok', 'La Factura ha sido Anulada.');
    			return $this->redirect($this->generateUrl('facturas_search'));
                }
                if($facturas and $fact_imv==NULL and $fact_cargo){
                    
                     $facturas->setEstado('X');
                     $facturas->setMotivo($motivo);
                     $facturas->setNfactura($nfactura);    

                     $fact_cargo->setEstado('X');
                        //$fact_imv->setEstado('X');

                        $em->persist($facturas);
                        $em->persist($fact_cargo);
                        //$em->persist($fact_imv);
                        $em->flush();

    			$this->get('session')->setFlash('ok', 'La Factura ha sido Anulada.');
    			return $this->redirect($this->generateUrl('facturas_search'));
	 		
	 	}
                if($facturas and $fact_cargo and $fact_imv){
                    
                    
                    $facturas->setEstado('X');
                    $facturas->setMotivo($motivo);
                    $facturas->setNfactura($nfactura);    

                    $fact_cargo->setEstado('X');
                    $fact_imv->setEstado('X');

                    $em->persist($facturas);
                    $em->persist($fact_cargo);
                    $em->persist($fact_imv);
                    $em->flush();

                    $this->get('session')->setFlash('ok', 'La Factura ha sido Anulada.');
    			return $this->redirect($this->generateUrl('facturas_search'));
                    
                }
                
                 if($facturas){
                    
                    
                    $facturas->setEstado('X');
                    $facturas->setMotivo($motivo);
                    $facturas->setNfactura($nfactura);    

                    $em->persist($facturas);
                    
                    $em->flush();

                    $this->get('session')->setFlash('ok', 'La Factura ha sido Anulada.');
    			return $this->redirect($this->generateUrl('facturas_search'));
                    
                }
                else{	
               $this->get('session')->setFlash('info', 'Los parametros de busqueda ingresados son incorrectos.');
	 			
	 		return $this->redirect($this->generateUrl('facturas_search'));
                }
            }    
    
   
	
	 public function rimprimirAction($factura1)
	{
		$em = $this->getDoctrine()->getEntityManager();
    	
                $factura = $em->getRepository('FacturacionBundle:Factura')->find($factura1);
    	
    	
                if (!$factura) {
                        throw $this->createNotFoundException('La factura solicitada no existe');
                }
    	
                $factura_cargo = $em->getRepository('FacturacionBundle:FacturaCargo')->findBy(array('factura' => $factura->getId()));    	
                $factura_imv = $em->getRepository('FacturacionBundle:FacturaImv')->findBy(array('factura' => $factura->getId()));    	

                $mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($factura->getPaciente()->getMupio());

                // se consulta por la informacion del profesional para ser visulizada en la factura.
                $profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
                $factura->setProfesional($profesional->getNombre().' '.$profesional->getApellido());

                $pdf = $this->get('white_october.tcpdf')->create();
                if($factura_cargo)
                {    
                $html = $this->renderView('FacturacionBundle:Factura:factura.pdf.twig',array(
                                                                        'factura' => $factura,
                                                                        'cargos' => $factura_cargo,
                                                                        'mupio' => $mupio
                ));
                }
                
                if($factura_imv){
                    
                    $html = $this->renderView('FacturacionBundle:Factura:factura_medicamento.pdf.twig',array(
                                                                        'factura' => $factura,
                                                                        'imvs' => $factura_imv,
                                                                        'mupio' => $mupio
                ));
                    
                    
                }
                
                
                 if($factura_cargo and $factura_imv)
                {    
                $html = $this->renderView('FacturacionBundle:Factura:facturau.pdf.twig',array(
                                                                        'factura' => $factura,
                                                                        'cargos' => $factura_cargo,
                                                                        'imvs' => $factura_imv,
                                                                        'mupio' => $mupio
                ));
                }
                

                return $pdf->quick_pdf($html, 'factura_venta_'.$factura->getId().'.pdf', 'D');  
}

        public function searchcfAction()
                
        {
            
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	    $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_searchcf"));
    	
            $form   = $this->createForm(new FacturascfType());

            $em = $this->getDoctrine()->getEntityManager();

            
            return $this->render('FacturacionBundle:Facturas:searchcf.html.twig', array(
    			'form'   => $form->createView()
            ));
        }
        
        
        public function listcfAction()
	{
            
                
		$request = $this->getRequest();
		$form    = $this->createForm(new FacturasType());
		$form->bindRequest($request);
		
		if ($form->isValid()) 
		{
			// se optienen todos los datos del formulario para ser procesado de forma individual 
			
			
		$idfactura = $form->get('factura')->getData();
	 	
	 	if(((trim($idfactura) && is_numeric($idfactura)))){
	 	
	 		$em = $this->getDoctrine()->getEntityManager();
	 		$factura = $em->getRepository('FacturacionBundle:Factura')->findOneBy(array('id' => $idfactura));
                        //die(var_dump($est_fact));
                        $breadcrumbs = $this->get("white_october_breadcrumbs");
                        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
                        $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_searchcf"));
                        $breadcrumbs->addItem($idfactura);

                        
	 		if(!$factura){
	 			$this->get('session')->setFlash('info', 'El número de factura no se encuentra.');
	 				
	 			return $this->redirect($this->generateUrl('facturas_searchcf'));
	 		}
                        
                        $est_fact = $factura->getEstado();

                        if ($est_fact=='X'){
                            
                            $this->get('session')->setFlash('info', 'Factura ya Anulada.');
	 				
	 		     return $this->redirect($this->generateUrl('facturas_searchcf'));
                            
                        }
	 			
	 		$dql = $em->createQuery("SELECT f
										FROM
											FacturacionBundle:Factura f 
											
										WHERE 
											f.id = :id
											");	 			
                        	 			
	 		$dql->setParameter('id', $factura->getId());
	 		$dql->getSql();
	 		$facturas = $dql->getResult();
	 		$clientes = $em->getRepository("ParametrizarBundle:Cliente")->findAll();

	 		if(!$facturas)
	 		{
	 			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');
	 			
				return $this->redirect($this->generateUrl('facturas_searchcf'));
	 		}
	 			
	 		return $this->render('FacturacionBundle:Facturas:listcf.html.twig', array(
	 				'facturas' => $facturas,
	 				'clientes' => $clientes,

	 				'form'   => $form->createView()
	 		));	 			
	 	}else{
	 		$this->get('session')->setFlash('info', 'Los parametros de busqueda ingresados son incorrectos.');
	 			
	 		return $this->redirect($this->generateUrl('facturas_searchcf'));
	 	}	 			
	}		
			                       

                
    }
    
     public function CambiarcfAction($factura1)

                {

                
        
            $em = $this->getDoctrine()->getEntityManager();

            $facturas = $em->getRepository('FacturacionBundle:Factura')->findoneBy(array('id'=> $factura1));
    	
            $fact_num = $facturas->getId();
            $cliente = $facturas->getCliente();
            $clienten = $cliente->getNombre(); 
          // die(var_dump($clienten));

            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
            $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_searchcf"));
            $breadcrumbs->addItem($fact_num,$this->get("router")->generate('facturas_searchcf'));
            $breadcrumbs->addItem("Anular");

            $form   = $this->createForm(new CambioCfType(), $facturas);
            //$fact = $form["factura"]->setData($fact_num);
            //$idpa = $form["idp"]->setData($id_paciente);
             //die(var_dump($fact));
            //$almacenf = $form["almacen"]->setData($nombre_almacen);
            return $this->render('FacturacionBundle:Facturas:cambiarcf.html.twig', array(
                            'facturas' => $facturas,
                            'form'   => $form->createView()
            ));
    }
    
     public function ConfirmarcfAction($factura1)

                {

                    $em = $this->getDoctrine()->getEntityManager();
                    $facturas = $em->getRepository('FacturacionBundle:Factura')->findoneBy(array('id'=> $factura1));
                    

                    $cliente = $facturas->getCliente();
                    $clienten = $cliente->getId();
                    $form = $this->createForm(new CambioCfType(), $facturas);
                    $request = $this->getRequest();
                    $form->bind($request);
                    $clientes = $form->get('cliente')->getData();
                    $clientec= $clientes->getId();
//die(var_dump($clientec));
                    
                if ($facturas ) {
                                       
                       // $fact_cargo->setEstado('X');
                        $facturas->setCliente($clientes);
                        $em->persist($facturas);
                        //$em->persist($fact_cargo);
                        $em->flush();

    			$this->get('session')->setFlash('ok', 'Cliente ha sido Modificado.');
    			return $this->redirect($this->generateUrl('facturas_searchcf'));
                }
               
                else{	
               $this->get('session')->setFlash('info', 'Los parametros de busqueda ingresados son incorrectos.');
	 			
	 		return $this->redirect($this->generateUrl('facturas_searchcf'));
                }
            }  
            
            
   public function searchproAction()
                
        {
            
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	    $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_searchpro"));
    	
            $form   = $this->createForm(new FacturasType());


            return $this->render('FacturacionBundle:Facturas:searchpro.html.twig', array(
    			'form'   => $form->createView()
            ));
        }    
        
        
        public function listproAction()
	{
            
                
		$request = $this->getRequest();
		$form    = $this->createForm(new FacturasType());
		$form->bindRequest($request);
		
		if ($form->isValid()) 
		{
			// se optienen todos los datos del formulario para ser procesado de forma individual 
			
			
		$idfactura = $form->get('factura')->getData();
	 	
	 	if(((trim($idfactura) && is_numeric($idfactura)))){
	 	
	 		$em = $this->getDoctrine()->getEntityManager();
	 		$factura = $em->getRepository('FacturacionBundle:Factura')->findOneBy(array('id' => $idfactura));
                        
                        //die(var_dump($est_fact));
                        $breadcrumbs = $this->get("white_october_breadcrumbs");
                        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
                        $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_searchpro"));
                        $breadcrumbs->addItem($idfactura);
                        $profesionalid= $factura->getProfesional();
                        
	 		$profesional = $em->getRepository("UsuarioBundle:Usuario")->findOneBy(array('id'=>$profesionalid));
                        $profesionalnombre= $profesional->getNombre();
                        $profesionalapellido= $profesional->getApellido();
                       // die(var_dump($profesionalapellido));
	 		if(!$factura){
	 			$this->get('session')->setFlash('info', 'El número de factura no se encuentra.');
	 				
	 			return $this->redirect($this->generateUrl('facturas_searchpro'));
	 		}
                        
                        $est_fact = $factura->getEstado();

                        if ($est_fact=='X'){
                            
                            $this->get('session')->setFlash('info', 'Factura Anulada');
	 				
	 		     return $this->redirect($this->generateUrl('facturas_searchpro'));
                            
                        }
	 			
	 		$dql = $em->createQuery("SELECT f
										FROM
											FacturacionBundle:Factura f 
											
										WHERE 
											f.id = :id
											");	 			
                        	 			
	 		$dql->setParameter('id', $factura->getId());
	 		$dql->getSql();
	 		$facturas = $dql->getResult();
	 		$clientes = $em->getRepository("ParametrizarBundle:Cliente")->findAll();

	 		if(!$facturas)
	 		{
	 			$this->get('session')->setFlash('info', 'La consulta no ha arrojado ningún resultado para los parametros de busqueda ingresados.');
	 			
				return $this->redirect($this->generateUrl('facturas_searchpro'));
	 		}
	 			
	 		return $this->render('FacturacionBundle:Facturas:listpro.html.twig', array(
	 				'facturas' => $facturas,
	 				'clientes' => $clientes,
                                        'nombrep' => $profesionalnombre,
                                        'apellidop' => $profesionalapellido,
	 				'form'   => $form->createView()
	 		));	 			
	 	}else{
	 		$this->get('session')->setFlash('info', 'Los parametros de busqueda ingresados son incorrectos.');
	 			
	 		return $this->redirect($this->generateUrl('facturas_searchpro'));
	 	}	 			
	}		
			                       

                
    }
    
    public function CambiarproAction($factura1)

                {

                
        
            $em = $this->getDoctrine()->getEntityManager();

            $facturas = $em->getRepository('FacturacionBundle:Factura')->findoneBy(array('id'=> $factura1));

            $fact_num = $facturas->getId();
            $est_fact = $facturas->getEstado();
           // $cliente = $facturas->getCliente();
            //$clienten = $cliente->getNombre(); 
           //die(var_dump(    $est_fact));

            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
            $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_searchpro"));
            $breadcrumbs->addItem($fact_num,$this->get("router")->generate('facturas_searchpro'));
            $breadcrumbs->addItem("Cambiar");
            if( $est_fact == "X" or $est_fact =="C")
                    {
                        $this->get('session')->setFlash('info', 'Factura  se encuentra cerrada o anulada');
    			return $this->redirect($this->generateUrl('facturas_searchpro'));
                        
                    }
                    
            $form   = $this->createForm(new CambioProType(), $facturas);
            //$fact = $form["factura"]->setData($fact_num);
            //$idpa = $form["idp"]->setData($id_paciente);
             //die(var_dump($fact));
            //$almacenf = $form["almacen"]->setData($nombre_almacen);
            return $this->render('FacturacionBundle:Facturas:cambiarpro.html.twig', array(
                            'facturas' => $facturas,
                            'form'   => $form->createView()
            ));
    }
    
    public function ConfirmarproAction($factura1)

                {

                    $em = $this->getDoctrine()->getEntityManager();
                    $facturas = $em->getRepository('FacturacionBundle:Factura')->findoneBy(array('id'=> $factura1));
                    $profesionalid= $facturas->getProfesional();

	 	    $profesional = $em->getRepository("UsuarioBundle:Usuario")->findOneBy(array('id'=>$profesionalid));

                    $form = $this->createForm(new CambioProType(), $facturas);
                    $request = $this->getRequest();
                    $form->bind($request);
                    $profesionalf = $form->get('usuarios')->getData();
                    $idprofesional= $profesionalf->getId();
                  //  die(var_dump($$est_fact));
                   
                    
                if ($facturas ) {
                                       
                       // $fact_cargo->setEstado('X');
                        $facturas->setProfesional($idprofesional);
                        $em->persist($facturas);
                        //$em->persist($fact_cargo);
                        $em->flush();

    			$this->get('session')->setFlash('ok', 'Profesional ha sido Modificado.');
    			return $this->redirect($this->generateUrl('facturas_searchpro'));
                }
               
                else{	
               $this->get('session')->setFlash('info', 'Los parametros de busqueda ingresados son incorrectos.');
	 			
	 		return $this->redirect($this->generateUrl('facturas_searchpro'));
                }
            }  

}
