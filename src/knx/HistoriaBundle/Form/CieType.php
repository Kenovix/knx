<?php

namespace knx\HistoriaBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CieType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('codigo', 'text',
						array('label' => 'Codigo',
								'attr' => array(
										'placeholder' => 'Ingrese el codigo',
										'autofocus' => 'autofocus')))
				->add('nombre', 'text',
						array('label' => 'Nombre',
								'attr' => array(
										'placeholder' => 'Ingrese el codigo',)));
	}

	/*setDefaultOptions() se indica el namespace de la entidad cuyos datos modifica este formulario.*/
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array('data_class' => 'knx\HistoriaBundle\Entity\Cie'));
	}

	public function getName() {
		return 'newCieType';
	}
}
