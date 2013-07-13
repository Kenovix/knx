<?php

namespace knx\HistoriaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HistoriaBundle\Entity\Medicamento
 *
 * @ORM\Table(name="medicamento")
 * @ORM\Entity
 * 
 * @ORM\Entity(repositoryClass="knx\HistoriaBundle\Entity\Repository\MedicamentoRepository")
 */
class Medicamento {
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @var string $principioActivo
	 * 
	 * @ORM\Column(name="principio_activo", type="string", length=200, nullable=false)
	 * @Assert\NotBlank()
	 * @Assert\Length(max=200)
	 */
	private $principioActivo;

	/**
	 * @var string $concentracion
	 * 
	 * @ORM\Column(name="concentracion", type="string", length=10, nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 * @Assert\Length(min=1,max=10)
	 */
	private $concentracion;

	/**
	 * @var string $presentacion
	 * 
	 * @ORM\Column(name="presentacion", type="string", length=30, nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 * @Assert\Length(min=1,max=30)
	 */
	private $presentacion;

	/**
	 * @var integer $posologia
	 * 
	 * @ORM\Column(name="posologia", type="string", length=100, nullable=true)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 * @Assert\Length(min=1,max=100)
	 * 
	 */
	private $posologia;

	/**
	 * @var boolean $estado
	 * @ORM\Column(name="estado", type="string", length=1, nullable=true)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 */
	private $estado;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set principioActivo
	 *
	 * @param string $principioActivo
	 * @return Medicamento
	 */
	public function setPrincipioActivo($principioActivo) {
		$this->principioActivo = $principioActivo;

		return $this;
	}

	/**
	 * Get principioActivo
	 *
	 * @return string 
	 */
	public function getPrincipioActivo() {
		return $this->principioActivo;
	}

	/**
	 * Set concentracion
	 *
	 * @param string $concentracion
	 * @return Medicamento
	 */
	public function setConcentracion($concentracion) {
		$this->concentracion = $concentracion;

		return $this;
	}

	/**
	 * Get concentracion
	 *
	 * @return string 
	 */
	public function getConcentracion() {
		return $this->concentracion;
	}

	/**
	 * Set presentacion
	 *
	 * @param string $presentacion
	 * @return Medicamento
	 */
	public function setPresentacion($presentacion) {
		$this->presentacion = $presentacion;

		return $this;
	}

	/**
	 * Get presentacion
	 *
	 * @return string 
	 */
	public function getPresentacion() {
		return $this->presentacion;
	}

	/**
	 * Set posologia
	 *
	 * @param string $posologia
	 * @return Medicamento
	 */
	public function setPosologia($posologia) {
		$this->posologia = $posologia;

		return $this;
	}

	/**
	 * Get posologia
	 *
	 * @return string 
	 */
	public function getPosologia() {
		return $this->posologia;
	}

	/**
	 * Set estado
	 *
	 * @param string $estado
	 * @return Medicamento
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

	public function __toString() {
		return $this->getPrincipioActivo() . '-' . $this->getPresentacion()
				. '-' . $this->getConcentracion() . '-' . $this->getPosologia();
	}
}