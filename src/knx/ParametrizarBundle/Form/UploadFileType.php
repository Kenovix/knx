<?php
namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UploadFileType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('cliente', 'entity', array(
				'class' => 'knx\ParametrizarBundle\Entity\Cliente',
				'empty_value' => 'Elige una aseguradora',
				'required' => true,
				'query_builder' => function (
						EntityRepository $repositorio) {
					return $repositorio
					->createQueryBuilder('c')
					->orderBy('c.nombre', 'ASC');
				}));
	}
	
	public function getName()
	{
		return 'uploadFile';
	}

}
