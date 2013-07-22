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
        	->add('cliente', 'choice', array('choices' => array('' => 'Seleccione cliente')))
            ->add('tipoActividad')
            ->add('catPyp')
            ->add('autorizacion')
            ->add('observacion')
            ->add('estado')
            ->add('profesional')
            ->add('created')
            ->add('updated')            
            ->add('usuario')
            ->add('servicio')
            ->add('hc')
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
