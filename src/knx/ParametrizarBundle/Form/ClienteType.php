<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit', 'text', array('label' => 'Nit: *', 'required' => true))
            ->add('nombre', 'text',	array('label' => 'Nombre: *' ,	 'required' => true))
            ->add('razon', 'text', array('label' => 'Razón social: *' , 'required' => true))
            ->add('tipo', 'choice', array('choices' => array('1' => 'Contributivo',
            												 '2' => 'Subsidiado',
            												 '3' => 'Vinculado', 
            												 '4' => 'Particular',
            												 '5' => 'Otro'
            		)))
            ->add('codigo', 'text', array('label' => 'Código Eps:', 'required' => false))
            ->add('telefono', 'integer', array('label' => 'Telefono:', 'required' => false))
            ->add('direccion', 'text', array('label' => 'Dirección:', 'required' => false))
            ->add('estado', 'choice', array('label' => 'Estado:', 'required' => true, 'choices' => array('A' => 'Activo', 'I' => 'Inactivo')))
            ->add('empresa', 'entity', array('required' => true, 'class' => 'knx\ParametrizarBundle\Entity\Empresa', 'empty_value' => 'Elige una empresa'))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\Cliente'
        ));
    }

    public function getName()
    {
        return 'gstCliente';
    }
}
