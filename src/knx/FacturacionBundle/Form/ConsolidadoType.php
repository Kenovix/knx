<?php

namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsolidadoType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		

		// opcion para consultar por el tipo de aseguradora q el usuario desee 
 		->add('cliente', 'entity', array(
 				'label' => 'Cliente :',
 				'attr' => array('class' => 'span4'),
 				'mapped' => false,
 				'class' => 'knx\ParametrizarBundle\Entity\Cliente',
 				'empty_value' => 'Consultar Todos Clientes',
 				'required' => true,
 				
 				))
 		
				 		
				 		
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
		return 'knx_reportes1';
	}
}
