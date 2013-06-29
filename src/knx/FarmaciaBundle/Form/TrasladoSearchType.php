<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TrasladoSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha_inicio',	'date', array('required' => true, 'label' => 'Fecha Inicio*'))        		
			->add('fecha_fin',	'date', array('required' => true, 'label' => 'Fecha Fin*'));
            
    }

    public function getName()
    {
        return 'gstSearchIngreso';
    }
}
