<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CargoPypType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('cargo', 'entity', array(
        								'required' => true,
        								'class' => 'knx\ParametrizarBundle\Entity\Cargo','attr'=>array('class'=>'input-xxlarge'),
					        			'query_builder' => function(EntityRepository $er) {
					        				return $er->createQueryBuilder('c')
					        					->orderBy('c.nombre', 'ASC');
					        			},
        								'empty_value' => 'Elige un cargo'))
        	->add('edadIni', 'integer',	array('label' => 'Edad inicio:' , 'required' => false))
        	->add('edadFin', 'integer',	array('label' => 'Edad fin:' , 'required' => false))
        	->add('rango', 'choice', array('choices'  => array('1' => '4,11,16 y 45', '2' => '55,65,70,75 y 80', '3' => '45, 50, 55,60,65,70,75,80'), 'empty_value' => 'Selecciona un rango', 'label' => 'Rango:', 'required' => false))
            ->add('sexo', 'choice', array('choices'  => array('empty_value' => 'Selecciona Sexo','M' => 'Masculino', 'F' => 'Femenino','A' => 'Ambos'), 'label'=>'Sexo','required'  => true))
            ->add('tipo', 'choice', array('mapped' => false,'empty_value' => 'Seleccione una opción', 'choices' => array('AC' => 'Consulta', 'AP' => 'Procedimientos')))
        	->add('tipo_cons', 'choice', array('required' => false,'label' => 'Finalidad consulta:', 'empty_value' => 'Seleccione una opción', 'choices' => array('01' => 'Atención del parto', '02' => 'Atención del recien nacido', '03' => 'Atención en planificación familiar', '04' => 'Detección de alteración de crecimiento y desarrollo en menor de 10 años', '05' => 'Detección de alteción del desarrollo del joven', '06' => 'Detección de alteración del embarazo', '07' => 'Detección de alteración del adulto', '08' => 'Detección de alteración de agudeza visual', '09' => 'Detección de enfermedad profesional')))
        	->add('tipoProc', 'choice', array('required' => false,'label' => 'Finalidad procedimiento:', 'empty_value' => 'Seleccione una opción', 'choices' => array('3' => 'protección especifica', '4' => 'Detección temprana de enfermedad general', '5' => 'Detección temprana de enfermedad profesional')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\CargoPyp'
        ));
    }

    public function getName()
    {
        return 'gstCargoPyp';
    }
}