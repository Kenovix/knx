<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlmacenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text',	array('label' => 'Nombre: *' ,	 'required' => true))
        	->add('empresa', 'entity', array('required' => true, 'class' => 'knx\ParametrizarBundle\Entity\Empresa', 'empty_value' => 'Elige una empresa'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\Almacen'
        ));
    }

    public function getName()
    {
        return 'gstAlmacen';
    }
}
