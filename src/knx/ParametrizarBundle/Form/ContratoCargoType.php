<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ContratoCargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('cargo', 'entity', array(
        								'required' => true, 
        								'class' => 'knx\ParametrizarBundle\Entity\Cargo', 
        								'query_builder' => function(EntityRepository $er) {
        									return $er->createQueryBuilder('c')
        										->orderBy('c.nombre', 'ASC');
        								},
        								'empty_value' => 'Elige un cargo'))
        	->add('precio', 'integer', array('label' => 'Precio:' , 'required' => false))
        	->add('estado', 'choice', array('choices' => array('A' => 'Activo', 'I' => 'Inactiva')))
        	->add('observacion', 'text', array('label' => 'Observación:' , 'required' => false))
        	;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\ContratoCargo'
        ));
    }

    public function getName()
    {
        return 'gstContratoCargo';
    }
}
