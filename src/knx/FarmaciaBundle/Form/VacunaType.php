<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VacunaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codCups',		  'text', 	array('label' => 'Codigo Cups:* ',	'required' => true, 'attr' => array('placeholder' => 'Ingrese Codigo')))
            ->add('nombre',			  'text', 	array('label' => 'Nombre: *',				'required' => true, 'attr' => array('placeholder' => 'Nombre')))
            ->add('jeringa',	  	  'text', 	array('label' => 'Jeringa:',			    'required' => false,'attr' => array('placeholder' => 'Jeringa')))
            ->add('dosis',		      'text', 	array('label' => 'Dosis:',				'required' => false, 'attr' => array('placeholder' => 'Dosis')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\FarmaciaBundle\Entity\Imv'
        ));
    }

    public function getName()
    {
        return 'gstVacuna';
    }
}
