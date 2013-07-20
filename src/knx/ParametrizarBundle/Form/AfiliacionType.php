<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AfiliacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('cliente', 'entity', array('class' => 'knx\ParametrizarBundle\Entity\Cliente', 'empty_value' => 'Elige una aseguradora', 'required' => true))
        ->add('observacion', 'text', array('required' => false, 'attr' => array('placeholder' => 'Ingrese alguna observación')))                
        ;
    }

    public function getName()
    {
        return 'newAfiliacion';
    }
}