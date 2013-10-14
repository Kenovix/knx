<?php
namespace knx\ParametrizarBundle\Controller;

use knx\ParametrizarBundle\Form\UploadFileType;

use knx\ParametrizarBundle\Form\PacienteType;
use knx\ParametrizarBundle\Entity\Paciente;
use knx\ParametrizarBundle\Form\AfiliacionType;
use knx\ParametrizarBundle\Entity\Afiliacion;
use knx\ParametrizarBundle\Form\pacienteSearchType;

use Symfony\Component\BrowserKit\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class PacienteController extends Controller
{
	public function newAction()
	{
		$entity = new Paciente();
		$form = $this->createForm(new PacienteType(), $entity);
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Nuevo");
	
		return $this->render('ParametrizarBundle:Paciente:new.html.twig',array(
				'form' => $form->createView()
		));
	}
	
	public function jxnewAction()
	{
		$entity = new Paciente();
		$form = $this->createForm(new PacienteType(), $entity);
	
		return $this->render('ParametrizarBundle:Paciente:jx_new.html.twig.twig',array(
				'form' => $form->createView()
		));
	}
	
	public function listAction($char)
	{
		$em = $this->getDoctrine()->getEntityManager();
		 
		$dql = $em->createQuery("SELECT p FROM ParametrizarBundle:Paciente p
						WHERE p.priNombre LIKE :nombre ORDER BY p.priNombre, p.priApellido ASC");
		 
		$dql->setParameter('nombre', $char.'%');
		$pacientes = $dql->getResult();
		
		$paginator = $this->get('knp_paginator');
		$pacientes = $paginator->paginate($pacientes, $this->getRequest()->query->get('page', 1),15);		 
	
		if(!$pacientes)
		{
			throw $this->createNotFoundException('La informacion solicitada no existe');
		}	
		 
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		
		return $this->render('ParametrizarBundle:Paciente:list.html.twig', array(
				'pacientes'  => $pacientes,
				'char' => $char,
		));
	}
	
	public function saveAction()
	{
		$paciente  = new Paciente();
		$request = $this->getRequest();		
		
		$form    = $this->createForm(new PacienteType(), $paciente);
		$form->bindRequest($request);		
		
		// Se convierte la fecha de naciemiento en una date se asigna al entity antes de ser validado
		$fN = date_create_from_format('d/m/Y',$form->get('fN')->getData());		
		$paciente->setFN($fN);
		
		if ($form->isValid()) {
			
			// se optinen los objetos mupio y depto para agregar su respectivo id
			$depto = $form->get('depto')->getData();
			$mupio = $form->get('mupio')->getData();			
			
			$paciente->setMupio($mupio->getId());
			$paciente->setDepto($depto->getId());			
						
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($paciente);
			$em->flush();
	
			$this->get('session')->setFlash('info', 'El paciente ha sido creado éxitosamente.');
	
			return $this->redirect($this->generateUrl('paciente_show', array("paciente" => $paciente->getId())));	
		}
	
		return $this->render('ParametrizarBundle:Paciente:new.html.twig', array(				
				'form'   => $form->createView()
		));
	
	}
	
	public function showAction($paciente)
	{
		$em = $this->getDoctrine()->getEntityManager();	
		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);
	
		if (!$paciente) {
			throw $this->createNotFoundException('El paciente solicitado no existe.');
		}
		
		$depto = $em->getRepository('ParametrizarBundle:Depto')->find($paciente->getDepto());
		$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($paciente->getMupio());		
		$paciente->setDepto($depto);
		$paciente->setMupio($mupio);
		
		// optengo de los metodos el valor de los campos
		$paciente->setPertEtnica($paciente->getPE($paciente->getPertEtnica()));
		$paciente->setNivelEdu($paciente->getNE($paciente->getNivelEdu()));
		$paciente->setTipoDes($paciente->getTD($paciente->getTipoDes()));
	
		$afiliaciones = $em->getRepository('ParametrizarBundle:Afiliacion')->findByPaciente($paciente);
			
		$afiliacion = new Afiliacion();	
		$form = $this->createForm(new AfiliacionType(), $afiliacion);
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Detalles", $this->get("router")->generate("paciente_show", array("paciente" => $paciente->getId() )));
		
		 
		return $this->render('ParametrizarBundle:Paciente:show.html.twig', array(
					'paciente' => $paciente,
		  			'afiliaciones' => $afiliaciones,
		  			'form' => $form->createView()
				));
	}
	
	public function editAction($paciente)
	{
		$em = $this->getDoctrine()->getEntityManager();	
		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);	
	
		if (!$paciente) {
			throw $this->createNotFoundException('El paciente solicitado no existe');
		}		
		
		$depto = $em->getRepository('ParametrizarBundle:Depto')->find($paciente->getDepto());
		$mupio = $em->getRepository('ParametrizarBundle:Mupio')->find($paciente->getMupio());
		$paciente->setDepto($depto);				
		$paciente->setMupio($mupio);		

		//---------------------------------
		if(!$paciente->getMovil())		// estas condicionales se usan para evitar posible problemas 
			$paciente->setMovil(NULL);	// entre la DB y la aplicacion ya q si se ah cargado info 
		if(!$paciente->getTelefono())	// con datos en vacios posiblemnte intentara convertir numeros q no existen 
			$paciente->setTelefono(NULL);
		//----------------------------------
		
		$paciente->setFN($paciente->getFN()->format('d/m/Y'));		

		$editForm = $this->createForm(new PacienteType(), $paciente);		
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Detalles", $this->get("router")->generate("paciente_show", array("paciente" => $paciente->getId() )));
		$breadcrumbs->addItem("Edit");
	
		return $this->render('ParametrizarBundle:Paciente:edit.html.twig', array(
				'paciente' 	  => $paciente,								
				'edit_form'   => $editForm->createView()
		));
	}
	
	public function updateAction($paciente)
	{
		$em = $this->getDoctrine()->getEntityManager();	
		$paciente = $em->getRepository('ParametrizarBundle:Paciente')->find($paciente);
	
		if (!$paciente) {
			throw $this->createNotFoundException('El paciente solicitado no existe.');
		}
		
		//---------------------------------
		if(!$paciente->getMovil())		// estas condicionales se usan para evitar posible problemas 
			$paciente->setMovil(NULL);	// entre la DB y la aplicacion ya q si se ah cargado info 
		if(!$paciente->getTelefono())	// con datos en vacios posiblemnte intentara convertir numeros q no existen 
			$paciente->setTelefono(NULL);
		//----------------------------------
	
		$editForm   = $this->createForm(new PacienteType(), $paciente);
		$request = $this->getRequest();
		$editForm->bindRequest($request);
	
		// Se convierte la fecha de naciemiento en una date se asigna al entity antes de ser validado
		$fN = date_create_from_format('d/m/Y',$editForm->get('fN')->getData());		
		$paciente->setFN($fN);
		
		if ($editForm->isValid()) {
			
			// se optinen los objetos mupio y depto para agregar su respectivo id				
			$paciente->setMupio($paciente->getMupio()->getId());
			$paciente->setDepto($paciente->getDepto()->getId());
				
			$em->persist($paciente);
			$em->flush();
	
			$this->get('session')->setFlash('info', 'La información del paciente ha sido modificada éxitosamente.');
	
			return $this->redirect($this->generateUrl('paciente_edit', array('paciente' => $paciente->getId())));
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Detalles", $this->get("router")->generate("paciente_show", array("paciente" => $paciente->getId() )));
		$breadcrumbs->addItem("Edit");
	
		return $this->render('ParametrizarBundle:Paciente:edit.html.twig', array(
				'paciente'    => $paciente,
				'edit_form'   => $editForm->createView(),
		));
	}	
	
	public function filtrarAction()
	{
		$request = $this->getRequest();
		$session = $this->getRequest()->getSession();
		$form   = $this->createForm(new pacienteSearchType());
		 
		if ($request->getMethod() == 'POST') {
	
			$form->bindRequest($request);
			 
			$tipoid = $form->get('tipoid')->getData();
			$id = $form->get('identificacion')->getData();
			$pri_nombre = $form->get('pri_nombre')->getData();
			$seg_nombre = $form->get('seg_nombre')->getData();
			$pri_apellido = $form->get('pri_apellido')->getData();
			$seg_apellido = $form->get('seg_apellido')->getData();
	
			$session->set('tipoid', $tipoid);
			$session->set('identificacion', $id);
			$session->set('pri_nombre', $pri_nombre);
			$session->set('seg_nombre', $seg_nombre);
			$session->set('pri_apellido', $pri_apellido);
			$session->set('seg_apellido', $seg_apellido);
	
		}else{
			$tipoid = $session->get('tipoid');
			$id = $session->get('identificacion');
			$pri_nombre = $session->get('pri_nombre');
			$seg_nombre = $session->get('seg_nombre');
			$pri_apellido = $session->get('pri_apellido');
			$seg_apellido = $session->get('seg_apellido');
		}
	
		$em = $this->getDoctrine()->getEntityManager();	
	
		$boolean = 0;
		$query = "SELECT p FROM ParametrizarBundle:Paciente p WHERE ";
		$parametros = array();
		 
		if($tipoid){
			$query .= "p.tipoId = :tipoid AND ";
			$parametros["tipoid"] = $tipoid;
		}else{
			$boolean ++;
		}
	
		if($id){
			$query .= "p.identificacion LIKE :id AND ";
			$parametros["id"] = $id.'%';
		}else{
			$boolean ++;
		}
		 
		if($pri_nombre){
			$query .= "p.priNombre LIKE :priNombre AND ";
			$parametros["priNombre"] = $pri_nombre.'%';
		}else{
			$boolean ++;
		}
		 
		if($seg_nombre){
			$query .= "p.segNombre LIKE :segNombre AND ";
			$parametros["segNombre"] = $seg_nombre.'%';
		}else{
			$boolean ++;
		}
		 
		if($pri_apellido){
			$query .= "p.priApellido LIKE :priApellido AND ";
			$parametros["priApellido"] = $pri_apellido.'%';
		}else{
			$boolean ++;
		}
		 
		if($seg_apellido){
			$query .= "p.segApellido LIKE :segApellido AND ";
			$parametros["segApellido"] = $seg_apellido.'%';
		}else{
			$boolean ++;
		}
		
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));		
		$breadcrumbs->addItem("Buscar");
		 
		if($boolean == 6){
			
			$this->get('session')->setFlash('info', 'Ingrese la información correspondiente para realizar la busqueda.');
			
			return $this->render('ParametrizarBundle:Paciente:filtro.html.twig', array(
					'entities'  => null,
					'form'   => $form->createView()
			));
		}
		
		$query = substr($query, 0, strlen($query)-4);		 
		$query .= " ORDER BY p.priNombre, p.priApellido ASC";		 
		$dql = $em->createQuery($query);		
			
		$dql->setParameters($parametros);			
		$pacientes = $dql->getResult();		
		$paginator = $this->get('knp_paginator');
		$pacientes = $paginator->paginate($pacientes, $this->getRequest()->query->get('page', 1),15);		
	
		return $this->render('ParametrizarBundle:Paciente:filtro.html.twig', array(
				'entities'  => $pacientes,
				'form'   => $form->createView()
		));
	}
	
	public function findDeptoAction()
	{
		$request = $this->get('request');
		$depto = $request->request->get('depto');
	
		if (is_numeric($depto)) {
				
			$em = $this->getDoctrine()->getEntityManager();
			$depto = $em->getRepository('ParametrizarBundle:Depto')->find($depto);
				
			if(!$depto)
			{
				throw $this->createNotFoundException('La informacion solicitada no existe');
			}
	
			$query = $em->createQuery('SELECT m FROM ParametrizarBundle:Mupio m WHERE m.depto = :depto ORDER BY m.municipio ASC ');
			$query->setParameter('depto', $depto->getId());
			$mupio = $query->getArrayResult();
	
			if ($mupio) {
					
				$response = array("responseCode" => 200);
	
				foreach ($mupio as $key => $value) {
					$response['mupio'][$key] = $value;
				}
	
			} else {
				$response = array("responseCode" => 400,"msg" => "No hay informacion para la opción seleccionada");
			}
			$return = json_encode($response);
			return new Response($return, 200,array('Content-Type' => 'application/json'));
		}
	}
	
	public function newCSVAction()
	{
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Subir Archivo");
		
		$form = $this->createForm(new UploadFileType());
		
		$this->get('session')->setFlash('info', 'Seleccione el archivo correspondiente para la carga de la informacion "FILE.CSV", "FILE.txt".');
		
		return $this->render('ParametrizarBundle:Paciente:file_new.html.twig',array(
							'nameFile' => null,
							'form' => $form->createView()
							));
	}
	
	public function uploadCSVAction()
	{
		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("Inicio", $this->get("router")->generate("parametrizar_index"));
		$breadcrumbs->addItem("Paciente", $this->get("router")->generate("paciente_list", array("char" => 'A')));
		$breadcrumbs->addItem("Subir Archivo");
		
		$request  = $this->getRequest();
		$form = $this->createForm(new UploadFileType());
		$registro = $request->get($form->getName());
		$form->bindRequest($request);

		// se consulta que las asociaciones sean correctas
		if (!$registro['tipoRegist'] || !$form->get('cliente')->getData()){
			throw $this->createNotFoundException('El paciente tiene que tener sus respectivas relaciones con el cliente tipo registro o regimen.');
		}
		// se asigna la variable del tipo de registro para enviarla y persistirla
		$tipoRegistro = $registro['tipoRegist'];
		
		// Se instancia la ruta en la cual se va a guardar el archivo
		$ruta = $this->container->getParameter('knx.directorio.uploads');		

		if(file_exists($ruta."datos-existentes-archivo.txt"))
			unlink($ruta."datos-existentes-archivo.txt");
		if(file_exists($ruta."errores-archivo.txt"))
			unlink($ruta."errores-archivo.txt");
		
		if($request->getMethod() == 'POST')
		{
			if ($_FILES['archivo']["error"] > 0) // se verifica que la carga del archivo no tenga errores				
			{
				$this->get('session')->setFlash('error', 'Ah ocurrido un error en el archivo. '.$_FILES['archivo']['error']);
				return $this->render('ParametrizarBundle:Paciente:file_new.html.twig',array(
							'nameFile' => null,
							'form' => $form->createView()
							));
				
			}else{
				
				// se verifica la existencia del archivo y se arroja una exception si el archivo no es soportado
				if($_FILES['archivo']['type'] == "text/csv" || $_FILES['archivo']['type'] == "text/txt")
				{					
				}else{
					throw $this->createNotFoundException('Error la extension del archivo no es valida solo se permiten file.csv y file.txt');
				}				
				
				// se genera la ruta y el archivo donde se van a guardar
				$archivo = $ruta.$_FILES['archivo']['name'];
								
				// se guarda el archivo en una carpeta ya q antes de guardar se encuentra en un temporal
				move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo);	

				// se carga el archivo para realizar su preparacion hacia la base de datos.
				$separador=',';
				$datos = file($archivo,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
								
				// se lee la primer linea de que corresponde a las cabeceras del archivo
				$cabeceras[]=explode($separador,array_shift($datos));							
				
				// se crea un array para separar los datos en un arreglo
				$DatosTemporal=array();
				
				foreach ($datos as $value) {
					$DatosTemporal[]=explode($separador,$value);					
				};				
								
				$em = $this->getDoctrine()->getEntityManager();
				$dql = $em->createQuery("SELECT p.identificacion FROM ParametrizarBundle:Paciente p ORDER BY p.identificacion DESC");
				$objPacientes = $dql->getArrayResult();
				
				// se verifica que los pacientes ingresados no existan en la base de datos.
				$result = $em->getRepository('ParametrizarBundle:Paciente')->validarInformacion($objPacientes, $DatosTemporal);				
				
				// se verifica que el archivo no contenga informacion redundante.
				$redundancia = $em->getRepository('ParametrizarBundle:Paciente')->fileSearchData($DatosTemporal);
				
				unlink($archivo);
				
				if($result)
				{			
					$nameFile = "datos-existentes-archivo.txt";			
					
					// se crea el archivo.
					$fp=fopen($ruta.$nameFile,"x");
					fwrite($fp,"Este Archivo Contiene Informacion Existente en la Base de Datos, Eliminela De Su Archivo Original y Vuelva a Cargar.\n");
					// se itera el array de los datos existentes en la base de datos y se visuaiza al usuario en un archivo plano	
					foreach ($result as $value)
						fwrite($fp,$value."\n");					
					fclose($fp);
					
					// Se envia la respectiva informacion para posteriormente descargarlo.					
					$this->get('session')->setFlash('error', 'Ah ocurrido un error en el archivo, hay información que ya existe en el archivo '.$_FILES['archivo']['name']);
					return $this->render('ParametrizarBundle:Paciente:file_new.html.twig',array(
							'nameFile' => $nameFile,
							'form' => $form->createView()
							));
					
				}elseif ($redundancia){
					$nameFile = "datos-existentes-archivo.txt";
						
					// se crea el archivo.
					$fp=fopen($ruta.$nameFile,"x");
					fwrite($fp,"Este Archivo Contiene Informacion Redundante, Eliminela De Su Archivo Original y Vuelva a Cargar.\n");
					// se itera el array de los datos existentes en la base de datos y se visuaiza al usuario en un archivo plano
					foreach ($redundancia as $value)
						fwrite($fp,$value."\n");
					fclose($fp);
						
					// Se envia la respectiva informacion para posteriormente descargarlo.
					$this->get('session')->setFlash('error', 'Ah ocurrido un error en el archivo, hay información redundante en el archivo '.$_FILES['archivo']['name']);
					return $this->render('ParametrizarBundle:Paciente:file_new.html.twig',array(
							'nameFile' => $nameFile,
							'form' => $form->createView()
					));
				}
				else{
					$cliente = $form->get('cliente')->getData();					
					$afiliacion = $this->verificarExistenciaDatos($DatosTemporal, $cliente, $tipoRegistro);
					$nameFile = "errores-archivo.txt";

					if($afiliacion)
					{
						$em->flush();
						
						foreach ($afiliacion as $key => $value){
							$em->persist($afiliacion[$key]);
						}
						$em->flush();					
							
						// Se envia la respectiva informacion para posteriormente descargarlo.
						$this->get('session')->setFlash('ok', 'La información del archivo'.$_FILES['archivo']['name'].' se guardo correctamente.');
						return $this->render('ParametrizarBundle:Paciente:file_new.html.twig',array(
								'nameFile' => $nameFile,
								'form' => $form->createView()
						));
					}
					
					// Se envia la respectiva informacion para posteriormente descargarlo.
					$this->get('session')->setFlash('error', 'La información del archivo'.$_FILES['archivo']['name'].' contiene informacion no congruente, compare con su plantilla y vuelva a intentar.');
					return $this->render('ParametrizarBundle:Paciente:file_new.html.twig',array(
							'nameFile' => $nameFile,
							'form' => $form->createView()
							));
				}			
			}	
		}
		return $this->render('ParametrizarBundle:Paciente:file_new.html.twig',array('form' => $form->createView()));
	}	
	
	public function verificarExistenciaDatos($insert, $cliente, $tipoRegistro)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$ruta = $this->container->getParameter('knx.directorio.uploads');
		// Archivo donde se almacena informacion sobre campos q le hacen falta atributo y camposo q estan vacios y son obligatorios.
		$fp=fopen($ruta."errores-archivo.txt","a");
		fwrite($fp,"Este Archivo Contiene Una Lista Con Informacion Incorrecta En Su Archivo Original, Modifique De Su Archivo Original y Vuelva a Cargar.\n");
		$arrayAfilia = array(); // array donde se guardan las afiliaciones
		
		foreach ($insert as $key => $value){
			
			// se verifica que las columnas estes complestas de cada fila	
			if(count($value) == 21)
			{				
				$tipoId 		= $em->getRepository('ParametrizarBundle:Paciente')->existTipoId($insert[$key][0]);
				$identificacion = $em->getRepository('ParametrizarBundle:Paciente')->existIdentificacion((int)$insert[$key][1]);
				$priNombre 		= $em->getRepository('ParametrizarBundle:Paciente')->existPriNombre($insert[$key][2]);
				$priApellido 	= $em->getRepository('ParametrizarBundle:Paciente')->existPriApellido($insert[$key][4]);
				$fn 			= $em->getRepository('ParametrizarBundle:Paciente')->existFN($insert[$key][6]);
				$sexo 			= $em->getRepository('ParametrizarBundle:Paciente')->existSexo($insert[$key][7]);
				$estadoCivil 	= $em->getRepository('ParametrizarBundle:Paciente')->existEstadoCivil($insert[$key][8]);
				$depto 			= $em->getRepository('ParametrizarBundle:Depto')->find($insert[$key][9]);
				$mupio 			= $em->getRepository('ParametrizarBundle:Mupio')->find($insert[$key][10]);
				$zona 			= $em->getRepository('ParametrizarBundle:Paciente')->existZona($insert[$key][12]);
				$ocupacion		= $em->getRepository('ParametrizarBundle:Ocupacion')->find($insert[$key][19]);				
								
				
				// Campos obligatorios que no pueden ir nulos
				if($tipoId && $identificacion && $priNombre && $priApellido && $fn && $sexo && $estadoCivil && $depto && $mupio && $zona && $ocupacion)
				{					
					$fn = date_create_from_format('Y-m-d',$insert[$key][6]);					
					
					// Se instancia el objeto paciente y listo para settiar
					$paciente = new Paciente();
					$paciente->setTipoId($insert[$key][0]);
					$paciente->setIdentificacion($insert[$key][1]);
					$paciente->setPriNombre($insert[$key][2]);
					$paciente->setSegNombre($insert[$key][3]);
					$paciente->setPriApellido($insert[$key][4]);
					$paciente->setSegApellido($insert[$key][5]);
					$paciente->setFN($fn);
					$paciente->setSexo($insert[$key][7]);
					$paciente->setEstaCivil($insert[$key][8]);
					$paciente->setDepto($insert[$key][9]);
					$paciente->setMupio($insert[$key][10]);
					$paciente->setDireccion($insert[$key][11]);
					$paciente->setZona($insert[$key][12]);
					$paciente->setTelefono($insert[$key][13]);
					$paciente->setMovil($insert[$key][14]);
					$paciente->setEmail($insert[$key][15]);
					$paciente->setPertEtnica($insert[$key][16]);
					$paciente->setNivelEdu($insert[$key][17]);
					$paciente->setOcupacion($ocupacion);
					$paciente->setTipoDes($insert[$key][20]);
					
					$afiliacion = new Afiliacion();
					$afiliacion->setPaciente($paciente);
					$afiliacion->setCliente($cliente);
					$afiliacion->setTipoRegist($tipoRegistro);
					
					$arrayAfilia[] = $afiliacion;					
					$em->persist($paciente);
					
				}
				else{
					// hay campos imcompletos en $value  linea $key por favor verifique la informacion y vuelva a cargar				
					fwrite($fp,implode(',',$value)."\n");				
				}
			}else{
				// faltan valores por ingresar en $value linea $key
				fwrite($fp,implode(',',$value)."\n");
			}						
		}
		fclose($fp);	
		return $arrayAfilia;	
	}	
	
	public function downloadFileAction($nameFile)
	{
		$ruta = $this->container->getParameter('knx.directorio.uploads');
		$downloadFile = $ruta.$nameFile;
		
		if(file_exists($downloadFile))
		{
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			header("Content-Type: text/plain");
			header("Content-Disposition: attachment; filename=$nameFile");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($downloadFile));			
			ob_clean();
			flush();
			readfile($downloadFile);			
		}else{
			$form = $this->createForm(new UploadFileType());
			
			$this->get('session')->setFlash('error', 'Ah ocurrido un error, el archivo que desea descargar no existe. ');
			return $this->render('ParametrizarBundle:Paciente:file_new.html.twig',array(
					'nameFile' => null,
					'form' => $form->createView()
			));
		}
		
	}
}
