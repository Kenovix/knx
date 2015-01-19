<?php
namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MorbilidadType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('dateStart', 'text', array(
										'label' => 'Fecha Inicial :',
										'attr' => array(
														'placeholder' => 'DD/MM/YYYY',
														'class' => 'span3'),
										'property_path' => false
		))
		->add('dateEnd',   'text', array(
										'label' => 'Fecha Final :',
										'attr' => array(
														'placeholder' => 'DD/MM/YYYY',
														'class' => 'span3'),
										'property_path' => false
		))
		
		->add('atencion', 'choice',
				array('label' => 'Tipo Atencion:',	
						'attr' => array('class' => 'span3'),
						'choices' => array(
								'T' => 'Todos',
								'primera_vez' => 'Primera vez',
								'repetida' => 'Repetida',),
						'multiple' => false,
		))
				
				
		->add('genero', 'choice',
				array('label' => 'Genero:',
						'attr' => array('class' => 'span3'),
						'choices' => array(
								'T'	  => 'Todos',
								'F'  => 'Femenino',
								'M'  => 'Masculino',
						)
				))
				
		->add('edadInicial', 'text', array(
										'label' => 'Edad Inicial :',
										'attr' => array(
														'placeholder' => '###',
														'class' => 'span3',
                                                                                                                'required'=>'false'),										
		))
		
		->add('edadFinal', 'text', array(
										'label' => 'Edad Final :',
										'attr' => array(
														'placeholder' => '###',
														'class' => 'span3',
                                                                                                                'required'=>false),										
		))
                        
                									
                        
                        
		
		->add('clinica', 'choice',
				array('label' => 'Clinica :',
						'attr' => array('class' => 'span3'),
						'choices' => array(
								'SA'	  => 'San Agustin',
						)
				))
				
		->add('centroCostos', 'choice',
				array('label' => 'C. Costo:',
						'attr' => array('class' => 'span3'),
						'choices' => array(
								'T'	 => 'Todos',
								'URGENCIAS' 		=> 'Urgencias',
								'CONSULTA EXTERNA' 	=> 'Consulta Externa',
								'HOSPITALIZACION' 	=> 'Hospitalizacion',
								'ODONTOLOGIA' 		=> 'Odontologia',
						)
				))		
		;
	}
	
	public function getName()
	{
		return 'knx_morbilidad';
	}

}
