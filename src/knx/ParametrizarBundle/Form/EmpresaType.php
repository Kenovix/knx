<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit', 'text', array('label' => 'Nit: *', 'required' => true))
            ->add('nombre', 'text',	array('label' => 'Nombre: *' ,	 'required' => true))
            ->add('habilitacion', 'text', array('label' => 'Código de habilitación: *' , 'required' => true))
            ->add('tipo', 'choice', array('choices' => array('PBC' => 'Publica', 'PVD' => 'Privada')))
            ->add('direccion', 'text', 	array('label' => 'Dirección:', 'required' => false))
            ->add('telefono', 'integer', 	array('label' => 'Telefono:', 'required' => false))
            ->add('depto', 'text', array('required' => true))
        	->add('mupio', 'text', array('required' => true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\Empresa'
        ));
    }

    public function getName()
    {
        return 'gstEmpresa';
    }
}
