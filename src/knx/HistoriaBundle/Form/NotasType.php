<?php

namespace knx\HistoriaBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotasType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) 
	{
		$builder
				->add('resumenNota', 'textarea',
						array('label' => 'Resumen Nota: *',
								'attr' => array(
										'placeholder' => 'Ingrese la informacion correspondiente a la nota')))
				/* SIGNOS */ 
				->add('fC', 'integer',
						array('required' => false, 'label' => 'FC:', 
								'attr' => array('placeholder' => 'entero')))
				->add('fR', 'integer',
						array('required' => false, 'label' => 'FR:',
								'attr' => array('placeholder' => 'entero')))
				->add('ta', 'text',
						array('required' => false, 'label' => 'T/A:',
								'attr' => array('placeholder' => '##/##')))
				->add('peso', 'integer',
						array('required' => false, 'label' => 'Peso:',
								'attr' => array('placeholder' => 'entero')))
				->add('estatura', 'integer',
						array('required' => false, 'label' => 'Talla:',
								'attr' => array('placeholder' => 'entero')))
				->add('imc', 'text',
						array('read_only' => true, 'required' => false,'label' => 'IMC:',
								'attr' => array('placeholder' => '# < 400') ))
				->add('temp', 'integer',
						array('required' => false, 'label' => 'Temp:',
								'attr' => array('placeholder' => 'entero'))) // temperatura corporal
				->add('pulso', 'integer',
						array('required' => false, 'label' => 'Pulso:',
								'attr' => array('placeholder' => 'entero')))
				->add('glasgow', 'text',
						array('required' => false, 'label' => 'Glasgow:',
								'attr' => array('placeholder' => '##/##'))) // escala q se asigna al paciente
				->add('triage', 'text',
						array('required' => false, 'label' => 'Triage:',
								'attr' => array('placeholder' => 'I,II,III,IV,V')))
		/* EndSignos */ 
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array('data_class' => 'knx\HistoriaBundle\Entity\Notas'));
	}

	public function getName() {
		return 'newNotasType';
	}
}
