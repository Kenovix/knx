<?php

namespace knx\HistoriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HistoriaBundle\Entity\HcExamen
 *
 * @ORM\Table(name="hc_examen")
 * @ORM\Entity
 * 
 */
class HcExamen {
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="knx\HistoriaBundle\Entity\Hc")
	 */
	private $hc;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="knx\HistoriaBundle\Entity\Examen")
	 */
	private $examen;

	/**
	 * @var datetime $fecha
	 *
	 * @ORM\Column(name="fecha", type="datetime", nullable=false)
	 */
	private $fecha;

	/**
	 * @var datetime $fecha_r
	 *
	 * @ORM\Column(name="fecha_r", type="date", nullable=true)     
	 */
	private $fecha_r;

	/**
	 * @var string $resultado
	 *
	 * @ORM\Column(name="resultado", type="string", length=150, nullable=true)     
	 */
	private $resultado;

	/**
	 * @var string $estado
	 *
	 * @ORM\Column(name="estado", type="string", length=150, nullable=false)
	 */
	private $estado;

	/**
	 * Set fecha
	 *
	 * @param datetime $fecha
	 */
	public function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	/**
	 * Get fecha
	 *
	 * @return datetime 
	 */
	public function getFecha() {
		return $this->fecha;
	}

	/**
	 * Set fecha_r
	 *
	 * @param datetime $fecha_r
	 */
	public function setFechaR($fecha_r) {
		$this->fecha_r = $fecha_r;
	}

	/**
	 * Get fecha_r
	 *
	 * @return datetime
	 */
	public function getFechaR() {
		return $this->fecha_r;
	}

	/**
	 * Set resultado
	 *
	 * @param string $resultado
	 */
	public function setResultado($resultado) {
		$this->resultado = $resultado;
	}

	/**
	 * Get resultado
	 *
	 * @return string 
	 */
	public function getResultado() {
		return $this->resultado;
	}

	/**
	 * Set estado
	 *
	 * @param string $estado
	 */
	public function setEstado($estado) {
		$this->estado = $estado;
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
	 * Set hc
	 *
	 * @param knx\HistoriaBundle\Entity\Hc $hc
	 */
	public function setHc(\knx\HistoriaBundle\Entity\Hc $hc) {
		$this->hc = $hc;
	}

	/**
	 * Get hc
	 *
	 * @return knx\HistoriaBundle\Entity\Hc 
	 */
	public function getHc() {
		return $this->hc;
	}

	/**
	 * Set examen
	 *
	 * @param knx\HistoriaBundle\Entity\Examen $examen
	 */
	public function setExamen(\knx\HistoriaBundle\Entity\Examen $examen) {
		$this->examen = $examen;
	}

	/**
	 * Get examen
	 *
	 * @return knx\HistoriaBundle\Entity\Examen 
	 */
	public function getExamen() {
		return $this->examen;
	}
}