<?php

namespace knx\HistoriaBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchPacienteType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('tipoid', 'choice',
						array('label' => 'Tipo ID:', 'property_path' => false,
								'choices' => array('cc' => 'CC', 'ti' => 'TI',
										'ce' => 'CE',), 'multiple' => false,))
				->add('cedula', 'integer',
						array('label' => 'Busqueda rapida:',
								'property_path' => false,
								'attr' => array(
										'placeholder' => 'Ingrese # de identificacion del paciente',
										'autofocus' => 'autofocus')));
	}

	public function getName() {
		return 'newSearchPacienteType';
	}
}
