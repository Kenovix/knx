<?php

namespace knx\FacturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use knx\ParametrizarBundle\Entity\Servicio;

/**
 * knx\FacturacionBundle\Entity\Factura
 *
 * @ORM\Table(name="factura")
 * @ORM\Entity
 */
class Factura
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var datetime $fecha
     * 
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var datetime $fR
     * 
     * @ORM\Column(name="f_r", type="datetime", nullable=true)
     */
    private $fR;
    
    /**
     * @var string $tipoActividad
     * 
     * @ORM\Column(name="tipo_actividad", type="string",  length=80, nullable=false)
     */
    private $tipoActividad;
    
     /**
     * @var string $catPyp
     * 
     * @ORM\Column(name="catPyp", type="string", length=30, nullable=true)
     */
    private $catPyp;    
    
    /**
     * @var string $autorizacion
     * 
     * @ORM\Column(name="autorizacion", type="string", length=30, nullable=true)
     */
    private $autorizacion;
    
    /**
     * @var Profesional
     *
     * @ORM\Column(name="profesional", type="integer", nullable=true)
     */
    private $profesional;
    

    /**
     * @var string $observacion
     *
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     */
    private $observacion;
    
    /**
     * @var Paciente
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Paciente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paciente_id", referencedColumnName="id")
     * })
     */
    private $paciente;
    
    /**
     * @var Cliente
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     * })
     */
    private $cliente;
    
     /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="knx\UsuarioBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;
    
    /**
     * @var servicio
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Servicio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="servicio_id", referencedColumnName="id")
     * })
     */
    private $servicio;    
    
    /**
     * @var hc
     *
     * @ORM\OneToOne(targetEntity="knx\HistoriaBundle\Entity\Hc", inversedBy="factura")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hc_id", referencedColumnName="id" )
     * })
     */
    private $hc;
    
     /** @var date $created
    *
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="created", type="datetime")
    */
   private $created;

     /**
      * @var datetime $updated
      *
      * @Gedmo\Timestampable(on="update")
      * @ORM\Column(name="updated", type="datetime")
      */
   private $updated;
}