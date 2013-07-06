<?php

namespace knx\HistoriaBundle\Form;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HcType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder

				->add('serviEgre', 'entity',
						array('label' => 'Servicio Egr: *',
								'class' => 'knx\\ParametrizarBundle\\Entity\\Servicio',
								'required' => true,
								'empty_value' => 'Servicio de Egreso'))
				/* Anamnesis */ 
				->add('tipoAtencion', 'choice',
						array('label' => 'Tipo Atencion:', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'primera_vez' => 'Primera vez',
										'repetida' => 'Repetida',),
								'multiple' => false,))
				->add('causaExt', 'choice',
						array('label' => 'Causa Externa: *',
								'required' => false,
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
								'attr' => array(
										'placeholder' => 'Ingrese la enfermedad actual del paciente')))
				->add('motivo', 'textarea',
						array('label' => 'Motivo consulta: *',
								'attr' => array(
										'placeholder' => 'Ingrese el motivo de la consulta')))
				->add('estadoGen', 'textarea',
						array('label' => 'Estado General:',
								'required' => false,
								'attr' => array('placeholder' => 'Estado Gen')))/* EndAnamnesis */ 

				/* Antecendentes*/
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
								'attr' => array('placeholder' => 'Alergias')))/* EndAntecendentes*/

				/* RevisionSistema*/
				->add('revSistema', 'textarea',
						array('label' => 'Revision del Sistema:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Revision del Sistema')))/* EndRevisionSistema*/ 

				/* ExamenFisico */
				->add('cabeza', 'textarea',
						array('label' => 'Cabeza:', 'required' => false,
								'attr' => array('placeholder' => 'Cabeza')))
				->add('cara', 'textarea',
						array('label' => 'Cutis:', 'required' => false,
								'attr' => array('placeholder' => 'Cutis')))
				->add('ojos', 'textarea',
						array('label' => 'Ojos:', 'required' => false,
								'attr' => array('placeholder' => 'Ojos')))
				->add('oidos', 'textarea',
						array('label' => 'Oidos:', 'required' => false,
								'attr' => array('placeholder' => 'Oidos')))
				->add('nariz', 'textarea',
						array('label' => 'Nariz:', 'required' => false,
								'attr' => array('placeholder' => 'Nariz')))
				->add('boca', 'textarea',
						array('label' => 'Boca:', 'required' => false,
								'attr' => array('placeholder' => 'Boca')))
				->add('cuello', 'textarea',
						array('label' => 'Cuello:', 'required' => false,
								'attr' => array('placeholder' => 'Cuello')))
				->add('torax', 'textarea',
						array('label' => 'Torax:', 'required' => false,
								'attr' => array('placeholder' => 'Torax')))
				->add('pulmones', 'textarea',
						array('label' => 'Pulmones:', 'required' => false,
								'attr' => array('placeholder' => 'Pulmones')))
				->add('abdomen', 'textarea',
						array('label' => 'Abdomen:', 'required' => false,
								'attr' => array('placeholder' => 'Abdomen')))
				->add('espalda', 'textarea',
						array('label' => 'Espalda:', 'required' => false,
								'attr' => array('placeholder' => 'Espalda')))
				->add('extremidades', 'textarea',
						array('label' => 'Extremidades:', 'required' => false,
								'attr' => array('placeholder' => 'Extremidades')))
				->add('genitales', 'textarea',
						array('label' => 'Genitales:', 'required' => false,
								'attr' => array('placeholder' => 'Genitales')))/* EndExamenFisico */

				/* Medico */
				/* Diagnosticos */ 

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
											->orderBy('c.nombre', 'ASC');
								}))
				->add('examenes', 'entity',
						array('mapped' => false,
								'label' => 'Seleccione los examenes:',
								'class' => 'knx\\HistoriaBundle\\Entity\\Examen',
								'required' => false,
								'empty_value' => '--examenes--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
											->createQueryBuilder('e')
											->orderBy('e.nombre', 'ASC');
								}))
				->add('laboratorio', 'entity',
						array('mapped' => false,
								'label' => 'Seleccione los medicamentos:',
								'class' => 'knx\\HistoriaBundle\\Entity\\Medicamento',
								'required' => false,
								'empty_value' => '--laboratorio--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
											->createQueryBuilder('l')
											->orderBy('l.principioActivo',
													'ASC');
								}))
				->add('tipoDx', 'choice',
						array('label' => 'Tipo Dx:', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'impresionDx' => 'Impresion diagnostica',
										'confirmadoNuevo' => 'Confirmado nuevo',
										'repetido' => 'Repetido',),
								'multiple' => false,)) /* EndDiagnosticos */

				->add('conducta', 'textarea',
						array('label' => 'Conducta:', 'required' => false,
								'attr' => array('placeholder' => 'Conducta')))
				->add('evolucion', 'textarea',
						array('label' => 'Evolucion:', 'required' => false,
								'attr' => array('placeholder' => 'Evolucion')))/* EndMedico*/

				/* Egreso */
				->add('dxSalida', 'textarea',
						array('label' => 'Dx Salida:', 'required' => false,
								'attr' => array(
										'placeholder' => 'Diagnostico Salida')))
				->add('condSalida', 'textarea',
						array('label' => 'Condicion Salidad:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Condicion Salidad')))
				->add('manejoSalida', 'textarea',
						array('label' => 'Manejo de Salida:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Manejo de Salida')))
				->add('destino', 'choice',
						array('label' => 'Destino:', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'domicilio' => 'Domicilio',
										'observacion' => 'Observación',
										'internacion' => 'Internación',
										'remision' => 'Remisión',
										'contraRemision' => 'Contra remisión',
										'muerte' => 'Muerte', 'otro' => 'Otro',),
								'multiple' => false,))/* EndEgreso */


			 // opciones para cuando el usuario selecciona la opcion de remision y contraremision
								
				->add('destino_r', 'text',
						array('label' => 'destino R:','required' => false,
								'attr' => array('placeholder' => 'destino de remisión')))
								
				->add('especialidad_r', 'text',
						array('label' => 'especialidad R:','required' => false,
								'attr' => array('placeholder' => 'Manejo de Salida')))
												
				->add('nuAuto_r', 'text',
						array('label' => '# Autorizacion:','required' => false,
								'attr' => array('placeholder' => 'numero de autorizacion para la remisión')))
																
				->add('descripcion_r', 'textarea',
						array('label' => 'descripcion R:','required' => false,
								'attr' => array('placeholder' => 'descripcion de la remisión')))
																				
				->add('servicio_r',  'choice',
						array('label' => 'Destino:', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'urgencia' => 'Urgencias',
										'ambulatoria' => 'Ambulatoria',),
								'multiple' => false,))
			// fin de las opciones para la remision y contraremision
								
			// opciones para la revision por sistema.
				->add('o_sentidos', 'textarea',
						array('label' => 'Organos sentidos:','required' => false,
								'attr' => array('placeholder' => 'Organos de los sentidos')))
								
				->add('a_respiratorio', 'textarea',
						array('label' => 'Aparato respiratorio:','required' => false,
								'attr' => array('placeholder' => 'Aparato Respiratorio')))
								
				->add('a_cardiovascular', 'textarea',
						array('label' => 'Aparato cardiovascular:','required' => false,
								'attr' => array('placeholder' => 'Aparato Cardiovascular')))
								
				->add('a_digestivo', 'textarea',
						array('label' => 'Aparato digestivo:','required' => false,
								'attr' => array('placeholder' => 'Aparato Digestivo')))
								
				->add('a_hematologico', 'textarea',
						array('label' => 'Aparato hematologico:','required' => false,
								'attr' => array('placeholder' => 'Aparato Hematologico')))
								
				->add('a_genitoUrinario', 'textarea',
						array('label' => 'Aparato genito urinario:','required' => false,
								'attr' => array('placeholder' => 'Aparato Genito Urinario')))
								
				->add('s_osteoarticular', 'textarea',
						array('label' => 'Sistema osteoarticular:','required' => false,
								'attr' => array('placeholder' => 'Sistema Osteoarticular')))
								
				->add('s_nervioso', 'textarea',
						array('label' => 'Sistema nervioso:','required' => false,
								'attr' => array('placeholder' => 'Sistema Nervioso')))
								
				->add('s_endocrino', 'textarea',
						array('label' => 'Sistema endocrino:','required' => false,
								'attr' => array('placeholder' => 'Sistema Endocrino')))
			// fin revision por sistema.
								
			// opciones para que los medicamentos y examenes sean asignados en la orden de salida
								
								
				->add('examenes_s', 'textarea',
						array('label' => 'Examenes salida:','required' => false,
								'attr' => array('placeholder' => 'Examenes que se le envian al paciente')))
				->add('procedimientos_s', 'textarea',
						array('label' => 'procedimientos salida:','required' => false,
								'attr' => array('placeholder' => 'Procedimientos que se le envian al paciente')))
				->add('medicamentos_s', 'textarea',
						array('label' => 'medicamentos salida:','required' => false,
								'attr' => array('placeholder' => 'medicametos que se le envian al paciente')))
								
				->add('sal_examenes', 'entity',
						array('mapped' => false,
								'label' => 'Seleccione los examenes:',
								'class' => 'knx\\HistoriaBundle\\Entity\\Examen',
								'required' => false,
								'empty_value' => '--examenes--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
									->createQueryBuilder('e')									
									->orderBy('e.nombre', 'ASC');
								}))
								
				->add('sal_procedimientos', 'entity',
						array('mapped' => false,
								'label' => 'Seleccione los procedimientos:',
								'class' => 'knx\\HistoriaBundle\\Entity\\Examen',
								'required' => false,
								'empty_value' => '--procedimientos--',
								'query_builder' => function (EntityRepository $repositorio) 
								{
									return $repositorio
									->createQueryBuilder('e')
									->where("e.tipo = :tipo")
									->setParameter('tipo', 'P')
									->orderBy('e.nombre', 'ASC');
								}))
				->add('sal_medicamentos', 'entity',
						array('mapped' => false,
								'label' => 'Seleccione los medicamentos:',
								'class' => 'knx\\HistoriaBundle\\Entity\\Medicamento',
								'required' => false,
								'empty_value' => '--laboratorio--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
									->createQueryBuilder('l')
									->orderBy('l.principioActivo',
											'ASC');
								}))
								
								
				->add('incapacidad', 'textarea',
						array('label' => 'Incapacidad','required' => false,
								'attr' => array('placeholder' => 'incapacidad del paciente ingrese la informacion correspondiente de los desde hasta etc.')))
								
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array('data_class' => 'knx\HistoriaBundle\Entity\Hc'));
	}

	public function getName() {
		return 'newHcType';
	}
}
