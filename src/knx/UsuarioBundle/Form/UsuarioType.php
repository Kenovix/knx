<?php

namespace knx\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class UsuarioType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)     {
        
    	$builder
    		->add('cc', 		'integer',array('label'=>'CC*', 'attr' => array('placeholder' => 'Número de cedula', 'autofocus'=>'autofocus')))
    		->add('nombre', 	'text', array('label'=>'Nombre*', 'attr' => array('placeholder' => 'Nombre')))
    		->add('apellido', 	'text', array('label'=>'Apellido*', 'attr' => array('placeholder' => 'Apellido')))
    		->add('roles', 'choice', array('label' => 'Rol', 'required' => true, 'choices' => array( 1 => 'SUPER ADMINISTRADOR', 2 => 'ADMINISTRADOR', 3 => 'FARMACIA', 4 => 'MEDICO', 5 => 'AUXILIAR', 6 => 'FACTURADOR', 7 => 'ODONTOLOGO'), 'multiple' => true))
    		->add('enabled', 'checkbox', array('label' => 'Estado', 'required' => false))
    		->add('especialidad', 'text', array('label'=>'Especialidad', 'required' => false))
    		->add('rm', 'text', array('label'=>'Registro médico', 'required' => false))
    		->add('cargo', 'choice', array('choices' => array('medico' => 'Médico', 'auxiliar' => 'Enfermera', 'odontologo' => 'Odontologo', 'facturador' => 'Facturador', 'farmaceuta' => 'Farmaceuta'), 'label'=>'Cargo', 'required' => false))
    	;
    	
    	parent::buildForm($builder, $options);

    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'knx\UsuarioBundle\Entity\Usuario'
    	));
    }

    public function getName() {
        return 'mi_user_registration';
    }
}