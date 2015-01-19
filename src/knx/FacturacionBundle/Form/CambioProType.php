<?php

namespace knx\FacturacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CambioProType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                  
                
          ->add('usuarios', 'entity', array(
 				'label' => 'Medico :',
 				'attr' => array('class' => 'span4'),
 				'mapped' => false,
 				'class' => 'knx\UsuarioBundle\Entity\Usuario',
 				'empty_value' => 'Consultar Todos Medicos',
 				'required' => false,
 				'query_builder' => function (
 						EntityRepository $repositorio) {
 							return $repositorio
 							->createQueryBuilder('u')
 							->where('u.roles LIKE :roles')
 							->setParameter('roles', '%ROLE_MEDICO%')
 							->orderBy('u.apellido', 'ASC');
 						}));
    }

    

    public function getName()
    {
        return 'newCambioProType';
    }
}
