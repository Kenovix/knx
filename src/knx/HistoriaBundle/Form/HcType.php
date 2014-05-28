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
								
				->add('examenes', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione los examenes:','required' => false,
								'attr' => array(
										'placeholder' => 'Busquedad por el Nombre del examen',
										'class' => 'span4 search-query',										
										'autocomplete' => 'off',
								)))
								
				->add('laboratorio', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione los medicamentos:','required' => false,
								'attr' => array(
										'placeholder' => 'Busquedad por el Nombre del laboratorio',
										'class' => 'span4 search-query',
										'autocomplete' => 'off',
								)))
								
				
				->add('tipoDx', 'choice',
						array('label' => 'Tipo Dx: *', 'required' => true,
								'choices' => array('' => '--seleccione--',
										'1' => 'Impresion diagnostica',
										'2' => 'Confirmado nuevo',
										'3' => 'Repetido',),
								'multiple' => false,)) /* EndDiagnosticos */

				->add('conducta', 'textarea',
						array('label' => 'Conducta:', 'required' => true,
								'attr' => array('placeholder' => 'Conducta')))
				->add('evolucion', 'textarea',
						array('label' => 'Evolucion:', 'required' => false,
								'attr' => array('placeholder' => 'Evolucion')))/* EndMedico*/

				/* Egreso */
		
				->add('dxSalida', 'text',
						array(	
								'label' => 'Seleccione el dx salida:',
								'required' => false,
								'read_only' => true,
								'attr' => array(
										'placeholder' => '##',
										'class' => 'span1',	
										'id' => 'disabledInput'
								)))
								
				->add('codeDxSalida', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione el dx salida:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Codigo',
										'class' => 'span1 search-query',										
										'autocomplete' => 'off',
								)))
								
				->add('nameDxSalida', 'text',
						array(	'mapped' => false,
								'label' => 'nombre del dx:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Busquedad por el Nombre del diagnostico',
										'class' => 'span6 search-query',										
										'autocomplete' => 'off',
								)))
								
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
						array('label' => 'Destino: *', 'required' => true,
								'choices' => array('' => '--seleccione--',
										'1' => 'Domicilio',	
										'2' => 'Pendiente',
										'4' => 'Remision',
										'3' => 'Otro',),
								'multiple' => false,))
				/* EndEgreso */


			 // opciones para cuando el usuario selecciona la opcion de remision y contraremision
								
				->add('destino_r', 'text',
						array('label' => 'Destino remision:','required' => false,
								'attr' => array('placeholder' => 'destino de remisión')))
								
				->add('especialidad_r', 'text',
						array('label' => 'Especialidad remision:','required' => false,
								'attr' => array('placeholder' => 'Manejo de Salida')))
												
				->add('nuAuto_r', 'text',
						array('label' => '# Autorizacion:','required' => false,
								'attr' => array('placeholder' => 'numero de autorizacion para la remisión')))
																
				->add('descripcion_r', 'textarea',
						array('label' => 'Descripcion remision:','required' => false,
								'attr' => array('placeholder' => 'descripcion de la remisión')))
								
				->add('rServicio', 'choice',
						array('label' => 'Servicio remision:', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'urgencia' => 'Urgencias',
										'ambulatorio' => 'Ambulatoria',),
								'multiple' => false,))
			// fin de las opciones para la remision y contraremision
								
			// opciones para la revision por sistema.
				->add('o_sentidos', 'text',
						array('label' => 'Organos sentidos:',
								'required' => true,								
								'attr' => array('placeholder' => 'Organos de los sentidos')))
								
				->add('a_respiratorio', 'text',
						array('label' => 'Aparato respiratorio:',
								'required' => true,								
								'attr' => array('placeholder' => 'Aparato Respiratorio')))
								
				->add('a_cardiovascular', 'text',
						array('label' => 'Aparato cardiovascular:',
								'required' => true,								
								'attr' => array('placeholder' => 'Aparato Cardiovascular')))
								
				->add('a_digestivo', 'text',
						array('label' => 'Aparato digestivo:',
								'required' => true,								
								'attr' => array('placeholder' => 'Aparato Digestivo')))
								
				->add('a_hematologico', 'text',
						array('label' => 'Aparato hematologico:',
								'required' => true,								
								'attr' => array('placeholder' => 'Aparato Hematologico')))
								
				->add('a_genitoUrinario', 'text',
						array('label' => 'Aparato genito urinario:',
								'required' => true,								
								'attr' => array('placeholder' => 'Aparato Genito Urinario')))
								
				->add('s_osteoarticular', 'text',
						array('label' => 'Sistema osteoarticular:',
								'required' => true,
								'data' => 'NORMAL',
								'attr' => array('placeholder' => 'Sistema Osteoarticular')))
								
				->add('s_nervioso', 'text',
						array('label' => 'Sistema nervioso:',
								'required' => true,								
								'attr' => array('placeholder' => 'Sistema Nervioso')))
								
				->add('s_endocrino', 'text',
						array('label' => 'Sistema endocrino:',
								'required' => true,								
								'attr' => array('placeholder' => 'Sistema Endocrino')))
			// fin revision por sistema.
								
			// opciones para que los medicamentos y examenes sean asignados en la orden de salida
								
								
				->add('examenes_s', 'textarea',
						array('label' => 'Examenes salida:','required' => false,
								'attr' => array('placeholder' => 'Recuerde ingresar primero todos los EXAMENES que se le envian al paciente antes de dar una descripcion.')))
				->add('procedimientos_s', 'textarea',
						array('label' => 'procedimientos salida:','required' => false,
								'attr' => array('placeholder' => 'Recuerde ingresar primero todos los PROCEDIMIENTOS que se le envian al paciente antes de dar una descripcion.')))
				->add('medicamentos_s', 'textarea',
						array('label' => 'medicamentos salida:','required' => false,
								'attr' => array('placeholder' => 'Recuerde ingresar primero todos los MEDICAMENTOS que se le envian al paciente antes de dar una descripcion.')))
								
								
				->add('sal_medicamentos', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione los medicamentos:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Busquedad por el Nombre del medicamento',
										'class' => 'span6 search-query',
										'autocomplete' => 'off',
								)))
				
				->add('sal_procedimientos', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione los procedimientos:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Busquedad por el Nombre del procedimiento',
										'class' => 'span6 search-query',
										'autocomplete' => 'off',
								)))
								
				->add('sal_examenes', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione los examenes:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Busquedad por el Nombre del examen',
										'class' => 'span6 search-query',										
										'autocomplete' => 'off',
								)))		
				
				
				/*->add('sal_examenes', 'entity',
						array('mapped' => false,
								'label' => 'Seleccione los examenes:',
								'class' => 'knx\\HistoriaBundle\\Entity\\Examen',
								'required' => false,
								'empty_value' => '--examenes y laboratorio--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
									->createQueryBuilder('e')		
									->where("e.tipo = :tipo")
									->setParameter('tipo', 'LB')
									->orderBy('e.nombre', 'ASC');
								}))*/
								
				/*->add('sal_procedimientos', 'entity',
						array('mapped' => false,
								'label' => 'Seleccione los procedimientos:',
								'class' => 'knx\\HistoriaBundle\\Entity\\Examen',
								'required' => false,
								'empty_value' => '--procedimientos--',
								'query_builder' => function (EntityRepository $repositorio) 
								{
									return $repositorio
									->createQueryBuilder('e')
									->where("e.tipo = :tipo OR e.tipo = :tipo1")
									->setParameter('tipo', 'P')
									->setParameter('tipo1', 'ID')
									->orderBy('e.nombre', 'ASC');
								}))*/
								
								
				->add('incapacidad', 'textarea',
						array('label' => 'Incapacidad','required' => false,
								'attr' => array('placeholder' => 'incapacidad del paciente ingrese la informacion correspondiente de los desde hasta etc.')))
								
								
			// formulario documentacion para el parto
				->add('p_Fecha_n', 'text',
						array('label' => 'Fecha Naciemiento: ','required' => false, 'mapped' => false,
								'attr' => array('placeholder' => 'DD/MM/YYYY HH:MM', 'class' => 'span2')))
								
				->add('pEdadG', 'integer',
						array('label' => 'Edad Gestacional: ','required' => false,
								'attr' => array('placeholder' => 'Edad Gestacional', 'class' => 'span2')))
								
				->add('pControlP', 'choice',
						array('label' => 'Control Prenatal: ', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'1' => 'SI',
										'2' => 'NO',),
								'attr' => array('class' => 'span2'),
								'multiple' => false,))

				->add('pSexo', 'choice',
						array('label' => 'Sexo: ', 'required' => false,
								'choices' => array('' => '--seleccione--',
										'M' => 'M',
										'F' => 'F',),
								'attr' => array('class' => 'span2'),
								'multiple' => false,))
								
				->add('pPeso', 'integer',
						array('label' => 'Peso: ','required' => false,
								'attr' => array('placeholder' => 'Peso', 'class' => 'span2')))								
				
				->add('pDx', 'text',
						array(
								'label' => 'Diagnóstico del recién nacido:',
								'required' => false,
								'read_only' => true,
								'attr' => array(
										'placeholder' => '##',
										'class' => 'span1',
										'id' => 'disabledInput'
								)))
				
				->add('codeDxpDx', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione el dx salida:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Codigo',
										'class' => 'span1 search-query',
										'autocomplete' => 'off',
								)))

				->add('nameDxpDx', 'text',
						array(	'mapped' => false,
								'label' => 'nombre del dx:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Busquedad por el Nombre del diagnostico',
										'class' => 'span6 search-query',
										'autocomplete' => 'off',
								)))
				/*->add('pDx', 'entity',
						array(  'attr' => array('class' => 'span4'),
								'label' => 'Diagnóstico del recién nacido: ',
								'class' => 'knx\\HistoriaBundle\\Entity\\Cie',
								'required' => false,
								'empty_value' => '--diagnosticos--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
									->createQueryBuilder('c')
									->orderBy('c.nombre', 'ASC');
								}))*/	
					
				->add('p_fecha_m', 'text',
						array('label' => 'Fecha Muerte: ','required' => false, 'mapped' => false,
								'attr' => array('placeholder' => 'DD/MM/YYYY HH:MM', 'class' => 'span2')))

				->add('pCausaM', 'text',
						array(
								'label' => 'Causa Muerte recién nacido:',
								'required' => false,
								'read_only' => true,
								'attr' => array(
										'placeholder' => '##',
										'class' => 'span1',
										'id' => 'disabledInput'
								)))
				
				->add('codeDxpCausaM', 'text',
						array(	'mapped' => false,
								'label' => 'Seleccione el dx salida:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Codigo',
										'class' => 'span1 search-query',
										'autocomplete' => 'off',
								)))

				->add('nameDxpCausaM', 'text',
						array(	'mapped' => false,
								'label' => 'nombre del dx:',
								'required' => false,
								'attr' => array(
										'placeholder' => 'Busquedad por el Nombre del diagnostico',
										'class' => 'span6 search-query',
										'autocomplete' => 'off',
								)))
				/*->add('pCausaM', 'entity',
						array(	'attr' => array('class' => 'span4'),								
								'label' => 'Causa Muerte recién nacido: ',
								'class' => 'knx\\HistoriaBundle\\Entity\\Cie',
								'required' => false,
								'empty_value' => '--diagnosticos--',
								'query_builder' => function (
										EntityRepository $repositorio) {
									return $repositorio
									->createQueryBuilder('c')
									->orderBy('c.nombre', 'ASC');
								}))*/				
								
			// fin formulario documentacion para el parto	

								
			// formulario para las opcionesque se despliegan una ves el paciente sale de observacion o hurgencias
				->add('destinoUserSalidaObser', 'choice',
						array('label' => 'Destino Usuario Salida Observacion: ',
								'required' => false,
								'choices' => array('' => '--seleccione--',
										'1' => 'Alta Urgencias',
										'2' => 'Remision',
										'3' => 'Hospitalización',),
								'attr' => array('class' => 'span2'),
								'multiple' => false,))
								
				->add('estadoSalida', 'choice',
						array('label' => 'Estado De La Salida: ',
								'required' => false,
								'choices' => array('' => '--seleccione--',
										'1' => 'Vivo',
										'2' => 'Muerto',),
								'attr' => array('class' => 'span2'),
								'multiple' => false,))
								
				->add('tipoDestino', 'choice',
						array('label' => 'Tipo Destino: ',								
								'choices' => array('' => '--seleccione--',
										'1' => 'Observacion',
										'2' => 'Hospitalización',),
								'attr' => array('class' => 'span3'),
								'multiple' => false,
								'expanded' => false,
								'required' => false, ))

				->add('viaIngresoInstitucion', 'choice',
						array('label' => 'Via De Ingreso A La Institucion: ',
								'required' => false,
								'choices' => array('' => '--seleccione--',
										'1' => 'Urgencias',
										'2' => 'Consulta Externa',
										'3' => 'Remitido',
										'4' => 'Nacido En La Institucion',),
								'attr' => array('class' => 'span2'),
								'multiple' => false,))							
			// find del formulario
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
