<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FarmaciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',		'text', 	array('label' => 'Nombre: *',		'required' => true, 'attr' => array('placeholder' => 'Ingrese Nombre')))
            ->add('observacion','textarea', array('label' => 'Observacion: *',	'required' => false, 'attr' => array('placeholder' => 'Ingrese alguna observacion')))
            ->add('estado', 'choice', array('label' => 'Estado:', 'required' => true, 'choices' => array('A' => 'Activo', 'I' => 'Inactivo')))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\FarmaciaBundle\Entity\Farmacia'
        ));
    }

    public function getName()
    {
        return 'gstFarmacia';
    }
}
