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



class FacturasController extends Controller
{
	public function searchAction()
                
        {
            
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
    	    $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_search"));
    	
            $form   = $this->createForm(new FacturasType());

            $em = $this->getDoctrine()->getEntityManager();
            $facturas = $em->getRepository('FacturacionBundle:Factura')->findAll();

            if (!$facturas) {
    		$this->get('session')->setFlash('info', 'Stock sin ingresos');
            }

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
			// se optienen todos los datos del formulario para ser procesado de forma individual 
			
			
		$idfactura = $form->get('factura')->getData();
	 	
	 	if(((trim($idfactura) && is_numeric($idfactura)))){
	 	
	 		$em = $this->getDoctrine()->getEntityManager();
	 		$factura = $em->getRepository('FacturacionBundle:Factura')->findOneBy(array('id' => $idfactura));
                        //die(var_dump($est_fact));
                        $breadcrumbs = $this->get("white_october_breadcrumbs");
                        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
                        $breadcrumbs->addItem("Buscar_Factura", $this->get("router")->generate("facturas_search"));
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
											f.id = :id
											");	 			
                        	 			
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
            $paciente = $facturas->getPaciente();
            $id_paciente = $paciente->getIdentificacion();
           
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
                    //die(var_dump($motivo));
                    
                if ($facturas and $fact_cargo==NULL and $fact_imv) {
                                       
                        $facturas->setEstado('X');
                       // $fact_cargo->setEstado('X');
                        $fact_imv->setEstado('X');
                        $facturas->setMotivo($motivo);

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
                $mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($factura->getPaciente()->getMupio());

                // se consulta por la informacion del profesional para ser visulizada en la factura.
                $profesional = $em->getRepository('UsuarioBundle:Usuario')->find($factura->getProfesional());
                $factura->setProfesional($profesional->getNombre().' '.$profesional->getApellido());

                $pdf = $this->get('white_october.tcpdf')->create();

                $html = $this->renderView('FacturacionBundle:Factura:factura.pdf.twig',array(
                                                                        'factura' => $factura,
                                                                        'cargos' => $factura_cargo,
                                                                        'mupio' => $mupio
                ));

                return $pdf->quick_pdf($html, 'factura_venta_'.$factura->getId().'.pdf', 'D');  
}
}
