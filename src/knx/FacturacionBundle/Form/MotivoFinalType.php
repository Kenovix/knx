<?php
namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MotivoFinalType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
                        
		->add('id', 'text', array('label' => 'Factura :','disabled' =>true))										
		->add('motivo', 'choice', array('empty_value' => 'Seleccione un motivo', 'choices' => array( 'Error Digitacion' => 'Error Digitacion', 'Cambio de Fecha' => 'Cambio de Fecha', 'Doble Facturaciòn' => 'Doble Facturaciòn',
                                                )))								
		->add('nfactura', 'text', array('label' => 'Factura Reemplaza :','attr' => array('placeholder' => 'Digite # Factura','class' => 'span3')));										

								
	}
	
	public function getName()
	{
		return 'knx_facturas';
	}

}
