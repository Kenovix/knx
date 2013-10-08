<?php

namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FacturaCargoType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('opcion', 'choice',
				 array('label' => 'Tipo de actividad :',
				 	   'attr' => array('class' => 'span4'),
				 	   'choices' => array(
				 	   				''	  => '-- select an option --',
				 	   				'IG'  => 'Informe General',
				 	   				'IR'  => 'Informe Regimen',				 	   				
				 	   				'IAR' => 'Informe Actividad Realizada',
				 	   				'ICRM'=> 'Informe Consultas por Medicos',
				 	   				'IRR' => 'Informe Remisiones Realizadas',
				 	   				'IM'  => 'Informe Morvilida',
				 	   				'BC'  => 'Boletin Cierre De Mes',				 	   				
				 	   				'IPS' => 'Informe Prestacion Servicios'			 	   		
				 	   		)
				 		))
				 		

		// opcion para consultar por el tipo de aseguradora q el usuario desee 
 		->add('cliente', 'entity', array(
 				'label' => 'Cliente :',
 				'attr' => array('class' => 'span4'),
 				'mapped' => false,
 				'class' => 'knx\ParametrizarBundle\Entity\Cliente',
 				'empty_value' => 'Consultar Todos Clientes',
 				'required' => false,
 				'query_builder' => function (
 						EntityRepository $repositorio) {
 						return $repositorio
 						->createQueryBuilder('c')
 						->orderBy('c.nombre', 'ASC');
 				}))
 		// opcion para consultar por el tipo de servicio 		
 		->add('servicio', 'entity', array(
 				'label' => 'Servicio :',
 				'attr' => array('class' => 'span4'),
 				'mapped' => false,
 				'class' => 'knx\ParametrizarBundle\Entity\Servicio',
 				'empty_value' => 'Consultar Todos Servicios',
 				'required' => false,
 				'query_builder' => function (
 						EntityRepository $repositorio) {
 						return $repositorio
 						->createQueryBuilder('s')
 						->orderBy('s.nombre', 'ASC');
 						}))
 						
 		// opcion para realizar informes consultas realizadas por medicos 						
 		->add('usuarios', 'entity', array(
 				'label' => 'Medico :',
 				'attr' => array('class' => 'span4'),
 				'mapped' => false,
 				'class' => 'knx\UsuarioBundle\Entity\Usuario',
 				'empty_value' => 'Consultar Todos Medicos',
 				'required' => false,
 				'query_builder' => function (
 						EntityRepository $repositorio) {
 							return $repositorio
 							->createQueryBuilder('u')
 							->where('u.roles LIKE :roles')
 							->setParameter('roles', '%ROLE_MEDICO%')
 							->orderBy('u.nombre', 'ASC');
 						}))
 						
 		//opcion para consultar por el tipo de regimen
 		->add('regimen', 'choice', array(
 				'attr' => array('class' => 'span4'),
 				'label' => 'Regimen :',
 				'mapped' => false,
 				'required' => false,
 				'choices' => array(
 					''  => 'Consultar Todo Regimen',	
 					'1' => 'Contributivo',
 					'2' => 'Subsidiado',
 					'3' => 'Vinculado',
 					'4' => 'Particular',
 					'5' => 'Otro'
 			))) 		
				 		
				 		
				 		
		->add('dateStart', 'text', array(
				'label' => 'Fecha Inicio :',
				'attr' => array(
						'placeholder' => 'DD/MM/YYYY',
						'class' => 'span4'),
				 'property_path' => false
				))
		->add('dateEnd',   'text', array(
				'label' => 'Fecha Fin :',
				'attr' => array(
						'placeholder' => 'DD/MM/YYYY',
						'class' => 'span4'),
				 'property_path' => false
				))				 		
		;
	}
	
	public function getName()
	{
		return 'knx_reportes';
	}
}
