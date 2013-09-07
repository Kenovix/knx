<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AfiliacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('cliente', 'entity', array(
        		'class' => 'knx\ParametrizarBundle\Entity\Cliente',
        		'empty_value' => 'Elige una aseguradora', 
        		'required' => true,
        		'query_builder' => function (
        				EntityRepository $repositorio) {
        			return $repositorio
        			->createQueryBuilder('c')
        			->orderBy('c.nombre', 'ASC');
        		}))
        ->add('tipoRegist', 'choice', array('label' => 'tipo registro:', 'required' => true, 'choices' => array('--' => '--')))
        ->add('observacion', 'text', array('required' => false, 'attr' => array('placeholder' => 'Ingrese alguna observación')))                
        ;
    }

    public function getName()
    {
        return 'newAfiliacion';
    }
}