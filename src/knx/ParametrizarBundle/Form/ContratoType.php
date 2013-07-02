<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('numero', 'text', array('required' => true, 'attr' => array('placeholder' => 'Ingrese el nombre del contacto', 'autofocus'=>'autofocus')))
        ->add('fechaInicio', 'date', array('required' => true))
        ->add('fechaFin', 'date', array('required' => true))
        ->add('contacto', 'date', array('required' => true, 'attr' => array('placeholder' => 'Ingrese el nombre del contacto')))        
        ->add('contacto', 'text', array('required' => true, 'attr' => array('placeholder' => 'Ingrese el nombre del contacto')))
        ->add('cargo', 'text', array('required' => false, 'attr' => array('placeholder' => 'Ingrese el cargo del contacto')))
        ->add('telefono', 'integer', array('required' => false, 'label' => 'Teléfono', 'attr' => array('placeholder' => 'Número telefonico')))
        ->add('celular', 'text', array('required' => false, 'attr' => array('placeholder' => 'Número movil')))
        ->add('email', 'text', array('attr' => array('placeholder' => 'Correo electrónico')))
        ->add('estado', 'choice', array('required' => true, 'choices' => array('A' => 'Activo', 'I' => 'Inactivo')))
        ->add('porcentaje', 'percent', array('required' => true, 'precision' => 0, 'attr' => array('placeholder' => 'Porcentaje pactado')))
        ->add('tipo', 'choice', array('required' => true, 'choices' => array('P' => 'Procedimientos', 'M' => 'Medicamentos')))
        ->add('observacion', 'text', array('required' => false, 'attr' => array('placeholder' => 'Ingrese observación del contacto')))                
        ;
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\Contrato'
        ));
    }

    public function getName()
    {
        return 'gstContrato';
    }
}
