<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text',	array('label' => 'Nombre: *' ,'disabled'=>true ,	 'required' => true))
            ->add('estado', 'choice', array('choices' => array('A' => 'Activo', 'I' => 'Inactivo')))
            ->add('empresa', 'entity', array('required' => true, 'class' => 'knx\ParametrizarBundle\Entity\Empresa', 'empty_value' => 'Elige una empresa','attr'=>array('class'=>'input-xxlarge'),'disabled'=>true))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\Servicio'
        ));
    }

    public function getName()
    {
        return 'gstServicio';
    }
}
