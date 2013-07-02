<?php

namespace knx\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioBasicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
    		->add('cc', 		'integer',array('label'=>'CC*','attr' => array('placeholder' => 'Numero de cedula')))
    		->add('nombre', 	'text', array('label'=>'Nombre*','attr' => array('placeholder' => 'Primer nombre...','autofocus'=>'autofocus')))
    		->add('apellido', 	'text', array('label'=>'Apellido*'))
    		->add('roles', 'choice', array('label' => 'Rol', 'required' => true, 'choices' => array( 1 => 'ROLE_SUPER_ADMIN', 2 => 'ROLE_ADMIN', 3 => 'ROLE_USER'), 'multiple' => true))
    		->add('enabled', 'checkbox', array('label' => 'Estado', 'required' => true))
    		->add('especialidad', 'text', array('label'=>'Especialidad'))
    		->add('rm', 'text', array('label'=>'Registro médico'))
    		->add('username', 'text', array('label'=>'Nombre de usuario'))
    	;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\UsuarioBundle\Entity\Usuario'
        ));
    }

    public function getName()
    {
        return 'gstUsuarioBasic';
    }
}
