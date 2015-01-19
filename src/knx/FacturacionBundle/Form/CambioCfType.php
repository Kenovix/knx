<?php

namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CambioCfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                  
                
            ->add('cliente', 		'entity', array(
            		'class' => 'knx\\ParametrizarBundle\\Entity\\Cliente',
            		'required' => true,'attr'=>array('class'=>'input-xxlarge'),
            		'empty_value' => 'Selecciona un Cliente',
            		'query_builder' => function(EntityRepository $repositorio) {
            			return $repositorio->createQueryBuilder('s')
            			->orderBy('s.nombre', 'ASC');},'label' => 'Cliente a Cambiar'
            ));
    }

    

    public function getName()
    {
        return 'newCambioCfType';
    }
}
