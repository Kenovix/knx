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
class Factura {
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
	 * @var string $tipoActividad
	 * 
	 * @ORM\Column(name="tipo_actividad", type="string",  length=80, nullable=false)
	 */
	private $tipoActividad;

	/**
	 * @var string $catPyp
	
	 * @ORM\Column(name="catPyp", type="string", length=30, nullable=true)
	 */
	private $catPyp;

	/**
	 * @var string $autorizacion
	
	 * @ORM\Column(name="autorizacion", type="string", length=30, nullable=true)
	 */
	private $autorizacion;

	/**
	 * @var string $observacion
	 *
	 * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
	 */
	private $observacion;
	
	/**
	 * @var string estado
	 *
	 * @ORM\Column(name="estado", type="string", length=2, nullable=false)
	 */
	private $estado;

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
	 * @var Profesional
	 *
	 * @ORM\Column(name="profesional", type="integer", nullable=true)
	 */
	private $profesional;

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
	 * @ORM\OneToOne(targetEntity="knx\HistoriaBundle\Entity\Hc", mappedBy="factura")
	 */
	private $hc;

	/** @var date $created
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(name="created", type="date")
	 */
	private $created;

	/**
	 * @var datetime $updated
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(name="updated", type="datetime")
	 */
	private $updated;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set fecha
	 *
	 * @param \DateTime $fecha
	 * @return Factura
	 */
	public function setFecha($fecha) {
		$this->fecha = $fecha;

		return $this;
	}

	/**
	 * Get fecha
	 *
	 * @return \DateTime 
	 */
	public function getFecha() {
		return $this->fecha;
	}

	/**
	 * Set tipoActividad
	 *
	 * @param string $tipoActividad
	 * @return Factura
	 */
	public function setTipoActividad($tipoActividad) {
		$this->tipoActividad = $tipoActividad;

		return $this;
	}

	/**
	 * Get tipoActividad
	 *
	 * @return string 
	 */
	public function getTipoActividad() {
		return $this->tipoActividad;
	}

	/**
	 * Set catPyp
	 *
	 * @param string $catPyp
	 * @return Factura
	 */
	public function setCatPyp($catPyp) {
		$this->catPyp = $catPyp;

		return $this;
	}

	/**
	 * Get catPyp
	 *
	 * @return string 
	 */
	public function getCatPyp() {
		return $this->catPyp;
	}

	/**
	 * Set autorizacion
	 *
	 * @param string $autorizacion
	 * @return Factura
	 */
	public function setAutorizacion($autorizacion) {
		$this->autorizacion = $autorizacion;

		return $this;
	}

	/**
	 * Get autorizacion
	 *
	 * @return string 
	 */
	public function getAutorizacion() {
		return $this->autorizacion;
	}

	/**
	 * Set observacion
	 *
	 * @param string $observacion
	 * @return Factura
	 */
	public function setObservacion($observacion) {
		$this->observacion = $observacion;

		return $this;
	}

	/**
	 * Get observacion
	 *
	 * @return string 
	 */
	public function getObservacion() {
		return $this->observacion;
	}
	
	/**
	 * Set estado
	 *
	 * @param string $estado
	 * @return Factura
	 */
	public function setEstado($estado) {
		$this->estado = $estado;
	
		return $this;
	}
	
	/**
	 * Get estado
	 *
	 * @return string
	 */
	public function getEstado() {
		return $this->estado;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 * @return Factura
	 */
	public function setCreated($created) {
		$this->created = $created;

		return $this;
	}

	/**
	 * Get created
	 *
	 * @return \DateTime 
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Set updated
	 *
	 * @param \DateTime $updated
	 * @return Factura
	 */
	public function setUpdated($updated) {
		$this->updated = $updated;

		return $this;
	}

	/**
	 * Get updated
	 *
	 * @return \DateTime 
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * Set paciente
	 *
	 * @param \knx\ParametrizarBundle\Entity\Paciente $paciente
	 * @return Factura
	 */
	public function setPaciente(
			\knx\ParametrizarBundle\Entity\Paciente $paciente) {
		$this->paciente = $paciente;

		return $this;
	}

	/**
	 * Get paciente
	 *
	 * @return \knx\ParametrizarBundle\Entity\Paciente 
	 */
	public function getPaciente() {
		return $this->paciente;
	}

	/**
	 * Set cliente
	 *
	 * @param \knx\ParametrizarBundle\Entity\Cliente $cliente
	 * @return Factura
	 */
	public function setCliente(\knx\ParametrizarBundle\Entity\Cliente $cliente) {
		$this->cliente = $cliente;

		return $this;
	}

	/**
	 * Get cliente
	 *
	 * @return \knx\ParametrizarBundle\Entity\Cliente 
	 */
	public function getCliente() {
		return $this->cliente;
	}

	/**
	 * Set usuario
	 *
	 * @param \knx\UsuarioBundle\Entity\Usuario $usuario
	 * @return Factura
	 */
	public function setUsuario(
			\knx\UsuarioBundle\Entity\Usuario $usuario) {
		$this->usuario = $usuario;

		return $this;
	}

	/**
	 * Get usuario
	 *
	 * @return \knx\UsuarioBundle\Entity\Usuario 
	 */
	public function getUsuario() {
		return $this->usuario;
	}

	/**
	 * Set profesional
	 *
	 * @param integer $profesional
	 * @return Factura
	 */
	public function setProfesional($profesional){
		
		$this->profesional = $profesional;
			
		return $this;
	}

	/**
	 * Get profesional
	 *
	 * @return integer 
	 */
	public function getProfesional() {
		return $this->profesional;
	}

	/**
	 * Set servicio
	 *
	 * @param \knx\ParametrizarBundle\Entity\Servicio $servicio
	 * @return Factura
	 */
	public function setservicio(
			\knx\ParametrizarBundle\Entity\Servicio $servicio) {
		$this->servicio = $servicio;

		return $this;
	}

	/**
	 * Get servicio
	 *
	 * @return \knx\ParametrizarBundle\Entity\Servicio 
	 */
	public function getServicio() {
		return $this->servicio;
	}

	/**
	 * Set hc
	 *
	 * @param \knx\HistoriaBundle\Entity\Hc $hc
	 * @return Factura
	 */
	public function setHc(\knx\HistoriaBundle\Entity\Hc $hc) {
		$this->hc = $hc;

		return $this;
	}

	/**
	 * Get hc
	 *
	 * @return \knx\HistoriaBundle\Entity\Hc 
	 */
	public function getHc() {
		return $this->hc;
	}
}