<?php

namespace knx\HistoriaBundle\Form;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OdontologiaType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder

		
		/* Start Anamnesis */ 
				->add('tipoAtencion', 'choice',
						array('label' => 'Tipo Atencion:', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'primera_vez' => 'Primera vez',
										'repetida' => 'Repetida',),
								'multiple' => false,))
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
				->add('estadoGen', 'textarea',
						array('label' => 'Estado General:',
								'required' => false,
								'attr' => array('placeholder' => 'Estado Gen')))
				/* End Anamnesis */ 

				/* Start Antecendentes*/
				->add('antecedentesGenerales', 'textarea',
						array('label' => 'Antecedentes Generales: *',
								'attr' => array(
										'placeholder' => 'Antecedentes Generales')))
				->add('antecedentesFisio', 'textarea',
						array('label' => 'Antecedentes Fisicos:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Antecedentes Fisicos')))
				->add('antecedentesGine', 'textarea',
						array('label' => 'Antecedentes Ginecologicos:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Antecedentes Ginecologicos')))
				->add('antecedentesPatologicos', 'textarea',
						array('label' => 'Antecedentes Patologicos:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Antecedentes Patologicos')))
				->add('antecedentesFami', 'textarea',
						array('label' => 'Antecedentes Familiares:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Antecedentes Familiares')))
				->add('habitosNocivos', 'textarea',
						array('label' => 'Habitos Nocivos:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Habitos Nocivos')))
				->add('inmunizaciones', 'textarea',
						array('label' => 'Inmunizaciones:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Inmunizaciones')))
				->add('alergias', 'textarea',
						array('label' => 'Alergias:', 'required' => false,
								'attr' => array('placeholder' => 'Alergias')))
				/* End Antecendentes*/	
						
		->add('dxPrin', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione los dx  :','required' => false,
								'attr' => array(
										'placeholder' => 'Codigo',
										'class' => 'span1 search-query',										
										'autocomplete' => 'off',
								)))
								
		->add('nameDxPrin', 'text',
				array(	'mapped' => false,
						'label' => 'nombre del dx:','required' => false,
						'attr' => array(
								'placeholder' => 'Busquedad por el Nombre del CIE',
								'class' => 'span6 search-query',
								//'class' => 'ui-autocomplete-input',
								'autocomplete' => 'off',
						)))
						
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