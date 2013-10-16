<?php

namespace knx\ParametrizarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use knx\ParametrizarBundle\Entity\CargoPyp;
use knx\ParametrizarBundle\Form\CargoPypType;
use Symfony\Component\HttpFoundation\Response;

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
    
    /**
     * @uses Función que consulta los cargos de pyp para una edad y un sexo dado.
     *
     * @param sexo, edad, cliente
     */
    public function jxBuscarCargoPypAction()
    {

    	$request = $this->get('request');
    	
    	$edad = $request->request->get('edad');
    	$sexo = $request->request->get('sexo');
    	$cliente = $request->request->get('cliente');
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$dql = $em->createQuery("SELECT 
    								c
    							FROM
    								knx\ParametrizarBundle\Entity\Contrato c    									
    							WHERE
    								c.cliente = :cliente AND
    								c.fechaInicio <= :fecha AND
    								c.fechaFin >= :fecha AND
    								c.tipo = 'PP' AND
    								c.estado = 'A'");
    	
    	$hoy = new \DateTime('now');
    	
    	$dql->setParameter("cliente", $cliente);
    	$dql->setParameter("fecha", $hoy);
    	
    	$contrato = $dql->getResult();
    	
    	if ($contrato) {

	    	if(is_numeric($edad)){
	    		
	    		$where = "";
	    		$parametros = array();
	    		$rango = 0;
	    		$rangoa = 0;
	    		
                        $rango = 0;
                        $rangoa = 0;
                        
	    		if(in_array($edad, array(4, 14, 16, 45))){
	    			
	    			$rango = 1;
	    			
	    			if(in_array($edad, array(45, 50 ,55, 60, 65, 70, 75, 80))){
	    				$rangoa = 3;
	    			}
	    		}
	    			
	    		if(in_array($edad, array(55, 65, 70, 75, 80))){
	    			$rango = 2;
	    			
	    			if(in_array($edad, array(45, 50 ,55, 60, 65, 70, 75, 80))){
	    				$rangoa = 3;
	    			}
 					
	    		}
	    		
	    		if(in_array($edad, array(45, 50 ,55, 60, 65, 70, 75, 80))){
	    			if ($rango != 3 && $rangoa != 3) {
	    				$rango = 3;
	    			}
	    		}
	    		
	    		if($rango == 1 || $rango == 2 || $rango == 3){
	    			$where .= "OR cp.rango = :rango";
	    			
	    			$parametros['rango'] = $rango;
	    		}

	    		if($rangoa){
	    			$where .= " OR cp.rango = :rangoa";
	    		
	    			$parametros['rangoa'] = $rangoa;
	    		}
	    		
	    		$parametros['edad'] = $edad;
	    		$parametros['sexo'] = $sexo;
	    		
	    		
	    		
	    		$query = $em->createQuery(" SELECT DISTINCT
	    										p.id,
	    										p.nombre 
	    									FROM 
	    										knx\ParametrizarBundle\Entity\CargoPyp cp
	    									JOIN
	    										cp.pyp p
	    									JOIN
	    										cp.cargo c
	    									WHERE 
	    									(cp.sexo = 'A' OR
	    									 cp.sexo = :sexo ) AND	
	    									(
	    										cp.edadIni <= :edad AND 
	    										cp.edadFin >= :edad 
	    									) AND ( 
	    									cp.edadIni <= :edad OR
	    									cp.edadFin >= :edad ) OR
	    									(cp.edadIni <= :edad AND
	    									cp.edadFin = '') AND
	    									c.tipoCargo = 'CE' ".$where);

	    		$query->setParameters($parametros);

	    		$cargos = $query->getResult();
	    	
	    		if($cargos){
	    			$response=array("responseCode" => 200);
					
	    			foreach ($cargos as $key => $value){
	    				$response['categoria'][$value['id']] = $value['nombre'];
	    			}
	    		}
	    		else{
	    			$response=array("responseCode"=>400, "msg"=>"No hay cargos disponibles para la edad dada.");
	    		}
	    	}else{
	    		$response=array("responseCode"=>400, "msg"=>"La edad no es valida.");
	    	}
    	}else{
    		$response=array("responseCode"=>400, "msg"=>"No hay un contrato vigente para las actividades de PYP con este cliente.");
    	}
	    	
    	$return=json_encode($response);
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    }
}