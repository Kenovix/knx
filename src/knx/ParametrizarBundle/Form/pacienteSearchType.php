<?php
namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class pacienteSearchType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('tipoid', 'choice', array('required' => false, 'label'=> 'Tipo ID:','mapped' => false,
				'choices' => array(
						''	 => '----',
						'RC' => 'RC',
						'TI' => 'TI',
						'CE' => 'CE',
						'CC' => 'CC',
						'PA' => 'PA',
						'MS' => 'MS',
						'AS' => 'AS',
						'NV' => 'NV',
						),
				'multiple'=>false,
		))
		->add('identificacion','text',array('label'=> 'Identificación', 'required'=>false, 'mapped' => false,
				'attr' => array('placeholder' => 'Ingrese identificación', 'autofocus'=>'autofocus')))
		->add('pri_nombre','text',array('label'=> 'Primer nombre', 'required'=>false, 'mapped' => false,
				'attr' => array('placeholder' => 'Ingrese primer nombre')))
		->add('seg_nombre','text',array('label'=> 'Segundo nombre', 'required'=>false, 'mapped' => false,
				'attr' => array('placeholder' => 'Ingrese segundo nombre')))
		->add('pri_apellido','text',array('label'=> 'Primer apellido', 'required'=>false, 'mapped' => false,
				'attr' => array('placeholder' => 'Ingrese primer apellido')))
		->add('seg_apellido','text',array('label'=> 'Segundo apellido', 'required'=>false, 'mapped' => false,
				'attr' => array('placeholder' => 'Ingrese segundo apellido')));
	}

	public function getName()
	{
		return 'searchPaciente';
	}
}
