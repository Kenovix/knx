<?php

namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FacturaCargoType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('opcion', 'choice',
				 array('label' => 'Tipo de actividad',
				 	   'attr' => array('class' => 'span4'),
				 	   'choices' => array(
				 	   				''	  => '-- select an option --',
				 	   				'IG'  => 'Informe General',
				 	   				'IR'  => 'Informe Regimen',
				 	   				'IAR' => 'Informe Actividad Realizada',
				 	   				'BC'  => 'Boletin Cierre De Mes',				 	   				
				 	   				'IPS' => 'Informe Prestacion Servicios'			 	   		
				 	   		)
				 		))
		->add('dateStart', 'text', array(
				'label' => 'Fecha Inicio',
				'attr' => array(
						'placeholder' => 'DD/MM/YYYY',
						'class' => 'span4'),
				 'property_path' => false
				))
		->add('dateEnd',   'text', array(
				'label' => 'Fecha Fin',
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
