<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IngresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha',	'date', array('required' => true, 'label' => 'Fecha Ingreso*','read_only' => true))
            ->add('almacen','entity', array('class' => 'knx\ParametrizarBundle\Entity\Almacen', 'empty_value' => 'Elige Almacen', 'required' => true))
            ->add('numFact','text', array('required' => true, 'label' => 'No Factura: *'))
            ->add('valorT',	'integer', array('required' => true, 'label' => 'Valor Total: *'))
            ->add('valorN',	'integer', array('required' => true, 'label' => 'Valor Neto: *'))
            ->add('valorIva','integer', array('required' => true, 'label' => 'Valor Iva %: *'))           
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\FarmaciaBundle\Entity\Ingreso'
        ));
    }

    public function getName()
    {
        return 'newIngreso';
    }
}
