<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CargoPypType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('cargo', 'entity', array('required' => true, 'class' => 'knx\ParametrizarBundle\Entity\Cargo', 'empty_value' => 'Elige un cargo'))
        	->add('edadIni', 'integer',	array('label' => 'Edad inicio:' , 'required' => false))
        	->add('edadFin', 'integer',	array('label' => 'Edad fin:' , 'required' => false))
        	->add('rango', 'choice', array('choices'  => array('1' => '4,11,16 y 45', '2' => '55,65,70,75 y 80', '3' => '45, 50, 55,60,65,70,75,80'), 'empty_value' => 'Selecciona un rango', 'label' => 'Rango:', 'required' => false))
            ->add('sexo', 'choice', array('choices'  => array('empty_value' => 'Selecciona Sexo','M' => 'Masculino', 'F' => 'Femenino','A' => 'Ambos'), 'label'=>'Sexo','required'  => true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\CargoPyp'
        ));
    }

    public function getName()
    {
        return 'gstCargoPyp';
    }
}