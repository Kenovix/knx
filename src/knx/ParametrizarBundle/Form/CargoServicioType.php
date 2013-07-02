<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CargoServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('cargo', 'entity', array('required' => true, 'class' => 'knx\ParametrizarBundle\Entity\Cargo', 'empty_value' => 'Elige un cargo'))
        	->add('observacion', 'text',	array('label' => 'Observación:' , 'required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\CargoServicio'
        ));
    }

    public function getName()
    {
        return 'gstCargoServicio';
    }
}
