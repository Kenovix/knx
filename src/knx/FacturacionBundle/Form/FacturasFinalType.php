<?php
namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FacturasFinalType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		
				
		->add('factura', 'text', array('label' => 'Factura :','attr' => array('placeholder' => 'Digite # Factura','class' => 'span3'),										
		));
		
		
		
		
	}
	
	public function getName()
	{
		return 'knx_facturas';
	}

}
