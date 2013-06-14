<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit', 'integer', array('label' => 'Nit: *', 'required' => true))
            ->add('nombre', 'text',	array('label' => 'Nombre: *' ,	 'required' => true))
            ->add('ciudad', 'text', array('label' => 'Ciudad:' , 'required' => true))
            ->add('direccion', 'text', 	array('label' => 'Dirección:', 'required' => false))
            ->add('telefono', 'integer', 	array('label' => 'Telefono:', 'required' => false))
            ->add('email', 'email', array('required' => false))
        	->add('almacen', 'entity', array('required' => true, 'class' => 'knx\ParametrizarBundle\Entity\Almacen', 'empty_value' => 'Elige un almacen'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\Proveedor'
        ));
    }

    public function getName()
    {
        return 'gstProveedor';
    }
}
