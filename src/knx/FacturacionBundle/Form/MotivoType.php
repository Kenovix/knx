<?php
namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MotivoType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
                        
		->add('id', 'text', array('label' => 'Factura :','disabled' =>true))										
		->add('motivo', 'choice', array('empty_value' => 'Seleccione un motivo', 'choices' => array('Cantidad Erronea' => 'Cantidad Erronea', 'Error Digitacion' => 'Error Digitacion', 'Paciente No Asiste' => 'Paciente No Asiste', 'Doble Facturaciòn' => 'Doble Facturaciòn',
                                                'Actividad Erronea' => 'Actividad Erronea')));									
		
		
		
		
		
	}
	
	public function getName()
	{
		return 'knx_facturas';
	}

}
