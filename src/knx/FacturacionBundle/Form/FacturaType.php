<?php

namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('paciente', 'text', array('attr' => array('placeholder' => 'Identificación', 'class' => 'span6'), 'property_path' => false))
        	->add('cliente', 'choice', array('disabled' => true, 'choices' => array('' => 'Seleccione cliente'), 'property_path' => false))
            ->add('pyp', 'choice', array('label' => 'Categoría de PYP', 'disabled' => true, 'choices' => array('' => 'Seleccione una categoría')))
            ->add('autorizacion')
            ->add('observacion')
            ->add('profesional', 'choice', array('label' => 'Profesional', 'disabled' => true, 'choices' => array('' => 'Seleccione un profesional')))
            ->add('servicio', 'entity', array('required' => true, 'class' => 'knx\ParametrizarBundle\Entity\Servicio', 'empty_value' => 'Elige un servicio'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\FacturacionBundle\Entity\Factura'
        ));
    }

    public function getName()
    {
        return 'knx_facturacionbundle_facturatype';
    }
}
