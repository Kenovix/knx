<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class IngresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha',	'date', array('required' => true, 'label' => 'Fecha Ingreso*'))
            ->add('almacen','entity', array('class' => 'knx\ParametrizarBundle\Entity\Almacen', 'empty_value' => 'Elige Almacen', 'required' => true))
            ->add('proveedor', 		'entity', array('class' => 'knx\\ParametrizarBundle\\Entity\\Proveedor','required' => true,'label' => 'Proveedor:',
            		'empty_value' => 'Selecciona un Proveedor',
            		'query_builder' => function(EntityRepository $repositorio) {
            			return $repositorio->createQueryBuilder('s')
            			->orderBy('s.nombre', 'ASC');}
            ))
            ->add('numFact','text', array('required' => true, 'label' => 'No Factura: *'))
            ->add('valorT',	'integer', array('required' => true, 'label' => 'Valor Total: *'))
            ->add('valorN',	'integer', array('required' => true, 'label' => 'Valor Neto: *'))
            ->add('valorIva','integer', array('required' => true, 'label' => 'Valor Iva: *'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\FarmaciaBundle\Entity\Ingreso'
        ));
    }

    public function getName()
    {
        return 'newIngreso';
    }
}
