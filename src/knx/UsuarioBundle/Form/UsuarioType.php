<?php

namespace knx\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class UsuarioType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)     {
        
    	parent::buildForm($builder, $options);

        $builder
        	->add('roles', 'choice', array('label' => 'Rol', 'required' => true, 'choices' => array( 1 => 'ROLE_ADMIN', 2 => 'ROLE_USER'), 'multiple' => true))
	        ->add('nombre', 	'text', array('label'=>'Nombre*','attr' => array('placeholder' => 'Primer nombre...','autofocus'=>'autofocus')))
	        ->add('apellido', 	'text', array('label'=>'Apellido*'))
	        ->add('cc', 		'integer',array('label'=>'CC*','attr' => array('placeholder' => 'Numero de cedula')));
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