<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo', 'choice', array('required' => false, 'label'=> 'Tipo Item:','property_path' => false,
				'choices' => array(
						'M'=> 'Medicamento',
						'I'=> 'Insumo',
						'V'=> 'Vacuna',
						'MP'=> 'Medicamentp_Pyp',),
				'multiple'=>false,'empty_value' => 'Seleccione Tipo'
		))
		->add('nombre','text',array('label'=> 'Nombre:', 'required'=>false, 'property_path' => false,
				'attr' => array('placeholder' => 'Ingrese Nombre', 'autofocus'=>'autofocus')));

    }

    public function getName()
    {
        return 'gstSearch';
    }
}
