<?php

namespace knx\HistoriaBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MedicamentoType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('principioActivo', 'text',
						array('label' => 'Nombre: *',
								'attr' => array(
										'placeholder' => 'Nombre del medicamento')))
				->add('concentracion', 'text',
						array('label' => 'Concentracion: *',
								'attr' => array(
										'placeholder' => 'Concentracion')))
				->add('presentacion', 'text',
						array('label' => 'Presentacion: *',
								'attr' => array('placeholder' => 'Presentacion')))
				->add('posologia', 'text',
						array('label' => 'Posologia: *',
								'attr' => array('placeholder' => 'Presentacion')))
				->add('estado', 'choice',
						array(
								'choices' => array('' => '--Seleccione--',
										'A' => 'Activo', 'I' => 'Inactivo')))
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array(
								'data_class' => 'knx\HistoriaBundle\Entity\Medicamento'));
	}

	public function getName() {
		return 'newMedicamentoType';
	}
}
