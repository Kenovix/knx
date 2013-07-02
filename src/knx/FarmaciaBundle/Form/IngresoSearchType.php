<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IngresoSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha_inicio',	'date', array('required' => true, 'label' => 'Fecha Inicio*'))        		
			->add('fecha_fin',	'date', array('required' => true, 'label' => 'Fecha Fin*'))
            ->add('num_fact','text', array('required' => false, 'label' => 'No Factura:'));
        
    }

    public function getName()
    {
        return 'gstSearchIngreso';
    }
}
