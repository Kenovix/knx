<?php

namespace knx\FarmaciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class SearchPypType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoria', 'entity', array('class' => 'knx\\ParametrizarBundle\\Entity\\Pyp','required' => true,'label' => 'Categoria:',
        		'empty_value' => 'Selecciona una categoria',
        		'query_builder' => function(EntityRepository $repositorio) {
        			return $repositorio->createQueryBuilder('s')
        			->orderBy('s.nombre', 'ASC');}));
			

    }

    public function getName()
    {
        return 'gstSearchPyp';
    }
}
