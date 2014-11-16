<?php

namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FacturacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('inicio', 'hidden', array('required' => true))
        ->add('fin', 'hidden', array('required' => true))        
        ->add('concepto', 'textarea', array('label' => 'Concepto', 'required' => true, 'attr' => array('autofocus'=>'autofocus')))
        ->add('observacion', 'textarea', array('required' => false))
        ->add('valor', 'hidden', array('required' => true))
        ->add('copago', 'hidden', array('required' => true))
        ->add('asumido', 'hidden', array('required' => true))        
        ->add('iva', 'hidden', array('required' => true))
        ;
    }

    public function getName()
    {
        return 'newFacturaFinal';
    }
}