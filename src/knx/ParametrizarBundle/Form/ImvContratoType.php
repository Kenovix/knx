<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImvContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('imv', 'entity', array('required' => true, 'class' => 'knx\FarmaciaBundle\Entity\Imv', 'empty_value' => 'Elige un medicamento'))
        	->add('precio', 'integer', array('label' => 'Precio:' , 'required' => false))
        	->add('estado', 'choice', array('choices' => array('A' => 'Activo', 'I' => 'Inactiva')))
        	->add('observacion', 'text', array('label' => 'Observación:' , 'required' => false))
        	;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\ImvContrato'
        ));
    }

    public function getName()
    {
        return 'gstImvContrato';
    }
}
