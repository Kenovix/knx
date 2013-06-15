<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImvPypType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('edadini',	'integer', array('required' => true, 'label' => 'Edad Inicio', 'attr' => array('placeholder' => 'Inicio')))
            ->add('edadfin',	'integer', array('required' => true, 'label' => 'Edad Final', 'attr' => array('placeholder' => 'Fin')))
            ->add('rango',		'integer', array('required' => true, 'label' => 'Rango', 'attr' => array('placeholder' => 'Numero telefonico')))
            ->add('sexo',		'choice',  array('choices'  => array('M' => 'Masculino', 'F' => 'Femenino','A' => 'Ambos'),'required'  => true, 'label'=>'Sexo'))
            ->add('jeringa',	'text',	   array('required' => true, 'label' => 'Jeringa', 'attr' => array('placeholder' => 'Jeringa')))
            ->add('dosis',		'text',    array('required' => true, 'label' => 'Dosis', 'attr' => array('placeholder' => 'Dosis')))      
            
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\FarmaciaBundle\Entity\ImvPyp'
        ));
    }

    public function getName()
    {
        return 'newImvPypType';
    }
}
