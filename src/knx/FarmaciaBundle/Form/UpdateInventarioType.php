<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class UpdateInventarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
       	  
       	    ->add('imv', 			'entity', array('class' => 'knx\\FarmaciaBundle\\Entity\\Imv','required' => true,'disabled' => true,'label' => 'Imv:',
        		'empty_value' => 'Selecciona un Imv',
        		'query_builder' => function(EntityRepository $repositorio) {
        			return $repositorio->createQueryBuilder('s')
        			->orderBy('s.nombre', 'ASC');}
        ))
            ->add('cant',			'integer', array('required' => true, 'label' => 'Cantidad: *', 'attr' => array('placeholder' => 'Cantidad')))
            ->add('precioCompra',	'integer', array('required' => true, 'label' => 'PrecioCompra: *', 'attr' => array('placeholder' => 'PrecioCom')))
            ->add('precioVenta',	'integer', array('required' => true, 'label' => 'PrecioVenta: *', 'attr' => array('placeholder' => 'PrecioVenta')))
           
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\FarmaciaBundle\Entity\Inventario'
        ));
    }

    public function getName()
    {
        return 'gstInventario';
    }
}
