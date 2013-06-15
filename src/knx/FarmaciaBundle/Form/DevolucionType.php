<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DevolucionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha',	'datetime', array('label' => 'Fecha Realizacion:', 'read_only' => true, 'label' => 'Fecha Devolucion:'))            
            ->add('cant',	'integer', 	array('label' => 'Cantidad: *', 'attr' => array('placeholder' => 'Cantidad', 'autofocus'=>'autofocus')))            
            ->add('motivo',	'text', 	array('label' => 'Motivo: *',   'attr' => array('placeholder' => 'Ingrese un motivo')))           
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\FarmaciaBundle\Entity\Devolucion'
        ));
    }

    public function getName()
    {
        return 'newDevolucionType';
    }
}
