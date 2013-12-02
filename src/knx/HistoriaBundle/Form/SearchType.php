<?php
namespace knx\HistoriaBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('nombre', 'text',
						array('label' => 'Busqueda rapida:',
								'property_path' => false,
								'attr' => array(
										'placeholder' => 'Ingrese el nombre de busqueda.',
										'autofocus' => 'autofocus')))
										
				->add('typeChoice', 'choice',
						array('label' => '*', 'required' => true,
								'property_path' => false,
								'choices' => array(
										'name' => 'Nombre',
										'cod' => 'Codigo',),
								'multiple' => false,))				
		;
	}

	public function getName() {
		return 'searchExamenFormType';
	}
}
