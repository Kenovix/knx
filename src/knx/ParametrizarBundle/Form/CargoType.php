<?php

namespace knx\ParametrizarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('soat', 'text', array('label' => 'Código SOAT: *', 'required' => true))
            ->add('cups', 'text', array('label' => 'Código CUPS: *', 'required' => true))
            ->add('nombre', 'text',	array('label' => 'Nombre: *' ,	 'required' => true))
            ->add('valor', 'integer', array('label' => 'Valor:', 'required' => false))
            ->add('rips', 'choice', array('empty_value' => 'Seleccione una opción', 'choices' => array('AC' => 'Consulta', 'AP' => 'Procedimientos', 'AT' => 'Otros Servicios')))
            ->add('tipo_cons', 'choice', array('label' => 'Finalidad consulta:', 'empty_value' => 'Seleccione una opción', 'choices' => array('01' => 'Atención del parto', '02' => 'Atención del recien nacido', '03' => 'Atención en planificación familiar', '04' => 'Detección de alteración de crecimiento y desarrollo en menor de 10 años', '05' => 'Detección de alteción del desarrollo del joven', '06' => 'Detección de alteración del embarazo', '07' => 'Detección de alteración del adulto', '08' => 'Detección de alteración de agudeza visual', '09' => 'Detección de enfermedad profesional', '10' => 'No aplica')))
            ->add('tipoProc', 'choice', array('label' => 'Finalidad procedimiento:', 'empty_value' => 'Seleccione una opción', 'choices' => array('1' => 'Diagnostico', '2' => 'Terapeutico', '3' => 'protección especifica', '4' => 'Detección temprana de enfermedad general', '5' => 'Detección temprana de enfermedad profesional')))
            ->add('tipoSer', 'choice', array('label' => 'Tipo de servicio:', 'empty_value' => 'Seleccione una opción', 'choices' => array('2' => 'Traslados', '3' => 'Estancias', '4' => 'Honorarios')))
        	->add('tipoCargo', 'choice', array('label' => 'Tipo de cargo:', 'empty_value' => 'Seleccione una opción', 'choices' => array('CE' => 'Consulta', 'CU' => 'Consulta urgencias', 'P' => 'Procedimiento', 'PO' => 'Odontología', 'LB' => 'Laboratorio', 'OS' => 'Otros Servicios')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'knx\ParametrizarBundle\Entity\Cargo'
        ));
    }

    public function getName()
    {
        return 'gstCargo';
    }
}
