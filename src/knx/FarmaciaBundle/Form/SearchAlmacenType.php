<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class SearchAlmacenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('almacen', 'entity', array('class' => 'knx\\ParametrizarBundle\\Entity\\Almacen','required' => true,'label' => 'Almacen:',
        		'empty_value' => 'Selecciona Almacen',
        		'query_builder' => function(EntityRepository $repositorio) {
        			return $repositorio->createQueryBuilder('s')
        			->orderBy('s.nombre', 'ASC');}));


    }

    public function getName()
    {
        return 'gstSearchAlmacen';
    }
}
