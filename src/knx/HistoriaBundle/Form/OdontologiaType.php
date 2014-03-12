<?php

namespace knx\HistoriaBundle\Form;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OdontologiaType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder

		->add('tipoAtencion', 'choice',
				array('label' => 'Tipo Atencion:', 'required' => false,
						'choices' => array('' => '--seleccione--',
								'primera_vez' => 'Primera vez',
								'repetida' => 'Repetida',),
						'multiple' => false,))
						
		->add('dxPrin', 'entity',
				array('mapped' => false,
						'label' => 'Seleccione los dx:',
						'class' => 'knx\\HistoriaBundle\\Entity\\Cie',
						'required' => false,
						'empty_value' => '--diagnosticos--',
						'query_builder' => function (
								EntityRepository $repositorio) {
							return $repositorio
							->createQueryBuilder('c')
							->where('c.codigo LIKE :A OR
									c.codigo LIKE :B OR 
									c.codigo LIKE :C OR 
									c.codigo LIKE :D OR 
									c.codigo LIKE :E OR 
									c.codigo LIKE :K OR 
									c.codigo LIKE :O OR 
									c.codigo LIKE :Q OR 
									c.codigo LIKE :S OR 
									c.codigo LIKE :T')
							->setParameter('A', 'A%')
							->setParameter('B', 'B%')
							->setParameter('C', 'C%')
							->setParameter('D', 'D%')
							->setParameter('E', 'E%')
							->setParameter('K', 'K%')
							->setParameter('O', 'O%')
							->setParameter('Q', 'Q%')
							->setParameter('S', 'S%')
							->setParameter('T', 'T%')
							->orderBy('c.codigo', 'ASC');
						}))
						
		->add('causaExt', 'choice',
				array('label' => 'Causa Externa: *',
						'required' => true,
						'choices' => array('' => '--seleccione--',
								'1' => 'Accidente de trabajo',
								'2' => 'Accidente de tránsito',
								'3' => 'Accidente rábico',
								'4' => 'Accidente ofídico',
								'5' => 'Otro tipo de accidente',
								'6' => 'Evento catastrófico',
								'7' => 'Lesión por agresión',
								'8' => 'Lesión auto infligida',
								'9' => 'Sospecha de maltrato físico',
								'10' => 'Sospecha de abuso sexual',
								'11' => 'Sospecha de violencia sexual',
								'12' => 'Sospecha de maltrato emocional',
								'13' => 'Enfermedad general',
								'14' => 'Enfermedad profesional',
								'15' => 'Otra',), 'multiple' => false,))
								
		->add('tipoDx', 'choice',
				array('label' => 'Tipo Dx: *', 'required' => true,
						'choices' => array('' => '--seleccione--',
								'1' => 'Impresion diagnostica',
								'2' => 'Confirmado nuevo',
								'3' => 'Repetido',),
						'multiple' => false,))
						
		->add('enfermedad', 'textarea',
				array('label' => 'Enfermedad actual: *',
						'required' => true,
						'attr' => array(
								'placeholder' => 'Ingrese la enfermedad actual del paciente')))
		->add('motivo', 'textarea',
				array('label' => 'Motivo consulta: *',
						'required' => true,
						'attr' => array(
								'placeholder' => 'Ingrese el motivo de la consulta')))
								
		->add('conducta', 'textarea',
				array('label' => 'Conducta:', 'required' => true,
						'attr' => array('placeholder' => 'Conducta')))
						
		;
	}
		
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
		->setDefaults(array('data_class' => 'knx\HistoriaBundle\Entity\Hc'));
	}
						
	public function getName(){
		return 'odontologiaType';
	}
}