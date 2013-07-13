<?php

namespace knx\HistoriaBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExamenType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('codigo', 'text',
						array('label' => 'Codigo*',
								'attr' => array(
										'placeholder' => 'codigo del examen'))) // cups
				->add('nombre', 'text',
						array('label' => 'Nombre*',
								'attr' => array(
										'placeholder' => 'Ingrese el nombre del examen'))) // nombre del examen
				->add('tipo', 'choice',
						array('label' => 'Tipo*',
								'choices' => array('' => '--seleccione--',
										'LB' => 'Laboratorio',
										'P' => 'Procedimiento',
										'ID' => 'Imagen Diagnostico',										
										),
								'multiple' => false,))// laboratorio o imagen diagnostica        		   
		;

	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array(
								'data_class' => 'knx\HistoriaBundle\Entity\Examen'));
	}

	public function getName() {
		return 'newExamenType';
	}
}
