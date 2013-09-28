<?php

namespace knx\ParametrizarBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PacienteType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('tipoId', 'choice',
						array('label' => 'Tipo Id:', 'required' => false,
								'choices' => array('' => '-Id-',
										'RC' => 'RC',
										'TI' => 'TI',
										'CE' => 'CE',
										'CC' => 'CC',
										'PA' => 'PA',
										'MS' => 'MS',
										'AS' => 'AS',
										'NV' => 'NV',
										),
								 'multiple' => false,))
				->add('identificacion', 'integer',
						array('required' => true, 'label' => 'Identificación:',
								'attr' => array(
										'placeholder' => 'numero de identificación')))
				->add('priNombre', 'text',
						array('required' => true, 'label' => 'Prim. Nombre:',
								'attr' => array(
										'placeholder' => 'Primer nombre')))
				->add('segNombre', 'text',
						array('required' => false, 'label' => 'Seg. Nombre:',
								'attr' => array(
										'placeholder' => 'Segundo nombre')))
				->add('priApellido', 'text',
						array('required' => true, 'label' => 'Prim. Apellido:',
								'attr' => array(
										'placeholder' => 'Primer apellido')))
				->add('segApellido', 'text',
						array('required' => false, 'label' => 'Seg. Apellido:',
								'attr' => array(
										'placeholder' => 'Segundo apellido')))
				->add('ocupacion', 'entity',
						array('label' => 'Ocupación: *',
								'class' => 'knx\\ParametrizarBundle\\Entity\\Ocupacion',
								'required' => true,
								'empty_value' => '--Ocupacion--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
									->createQueryBuilder('o')
									->orderBy('o.nombre', 'ASC');
								}))	

				->add('fN', 'text',
						array(	'mapped' => false,
								'required' => true,
								'label' => 'Fecha Nacimiento:',
								'attr' => array(
										'placeholder' => 'DD/MM/YYYY')))
				
				->add('sexo', 'choice',
						array('label' => 'Sexo:', 'required' => true,
								'choices' => array('' => 'Sexo',
										'M' => 'M', 'F' => 'F',),
								'multiple' => false,))
				->add('estaCivil', 'choice',
						array('label' => 'Estado Civil:', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'SOLTERO' => 'Soltero',
										'CASADO' => 'Casado',
										'UNION_LIBRE' => 'Union libre',),
								'multiple' => false,))
				->add('depto', 'entity',
						array('label' => 'Departamento: *',
								'class' => 'knx\\ParametrizarBundle\\Entity\\Depto',
								'required' => true,
								'empty_value' => '--Departamento--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
											->createQueryBuilder('d')
											->orderBy('d.nombre', 'ASC');
								}))
				->add('mupio', 'entity',
						array('label' => 'Municipio: *',
								'class' => 'knx\\ParametrizarBundle\\Entity\\Mupio',
								'required' => true,
								'empty_value' => '--municipio--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
											->createQueryBuilder('m')
											->orderBy('m.municipio', 'ASC');
								}))
				->add('direccion', 'text',
						array('required' => false, 'label' => 'Dirección',
								'attr' => array('placeholder' => 'Domicilio')))
				->add('zona', 'choice',
						array('label' => 'Zona:', 'required' => true,
								'choices' => array(
										'' => 'Zona',
										'U' => 'Urbana',
										'R' => 'Rural',),
								'multiple' => false,))
				->add('telefono', 'text',
						array('required' => false, 'label' => 'Teléfono',
								'attr' => array(
										'placeholder' => 'Número teléfonico')))
				->add('movil', 'text',
						array('required' => false, 'label' => 'Movil',
								'attr' => array('placeholder' => 'Número móvil')))
				->add('email', 'email',
						array('required' => false, 'label' => 'Email',
								'attr' => array(
										'placeholder' => 'Ejemplo@ejemplo.com')))				
										
				->add('pertEtnica', 'choice',
						array('label' => 'Pertenencia Etnica:', 'required' => false,
								'choices' => array(
										'' => '--Pertenencia Etnica--',
										'1' => 'Indígena',
										'2' => 'ROM (gitano)',
										'3' => 'Raizal (archipiélago de San Andrés y Providencia)',
										'4' => 'Palanquero de San  Basilio',
										'5' => 'Negro(a), Mulato(a),Afrocolombiano(a) o Afrodescendiente',
										'6' => 'Ninguno de los anteriores',
										),
								 'multiple' => false,))
				->add('nivelEdu', 'choice',
						array('label' => 'Nivel educativo:', 'required' => true,
								'choices' => array('' => '--nivel Edu--',
										'1' => 'No Definido',
										'2' => 'Preescolar',
										'3' => 'Basica Primaria',
										'4' => 'Basica Secundaria(Bachillerato Basico)',
										'5' => 'Media Academica o Clásica (Bachillerato Basico)',
										'6' => 'Media Tecnica (Bachillerato Tecnico)',
										'7' => 'Normalista',
										'8' => 'Tecnica Profesional',
										'9' => 'Tecnologica',
										'10' => 'Profesional',
										'11' => 'Especializacion',
										'12' => 'Maestria',
										'13' => 'Doctorado', 
										),
								 'multiple' => false,))
								 
				 ->add('tipoDes', 'choice',
				 		array('label' => 'tipo Desplazado:', 'required' => false,
				 				'choices' => array('' => '--tipo Des--',
				 						'6' => 'Des.Contributivo',
				 						'7' => 'Des.Subsidiado',
				 						'8' => 'Des.Vinculado',
				 				),
				 				'multiple' => false,))

		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array(
								'data_class' => 'knx\ParametrizarBundle\Entity\Paciente'));
	}

	public function getName() {
		return 'knxPacientetype';
	}
}
