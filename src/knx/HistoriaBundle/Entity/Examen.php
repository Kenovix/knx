<?php

namespace knx\HistoriaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HistoriaBundle\Entity\Examen
 *
 * @ORM\Table(name="examen")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="knx\HistoriaBundle\Entity\Repository\ExamenRepository")
 */
class Examen {
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @var string $codigo
	 *
	 * @ORM\Column(name="codigo", type="string", length=7, nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 * @Assert\MaxLength(limit=7, message="El valor ingresado debe tener maximo {{ limit }} caracteres.")
	 */
	private $codigo;

	/**
	 * @var string $nombre
	 *
	 * @ORM\Column(name="nombre", type="string", length=60, nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 * @Assert\MaxLength(limit=60, message="El valor ingresado debe tener maximo {{ limit }} caracteres.")
	 */
	private $nombre;

	/**
	 * @var integer $tipo
	 *
	 * @ORM\Column(name="tipo", type="string", length=2, nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 * @Assert\Choice(choices = {"LB", "ID", "P"}, message = "Selecciona una opcion valida.")
	 */
	private $tipo;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set codigo
	 *
	 * @param integer $codigo
	 */
	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	/**
	 * Get codigo
	 *
	 * @return string 
	 */
	public function getCodigo() {
		return $this->codigo;
	}

	/**
	 * Set nombre
	 *
	 * @param string $nombre
	 */
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	/**
	 * Get nombre
	 *
	 * @return string 
	 */
	public function getNombre() {
		return $this->nombre;
	}

	/**
	 * Set tipo
	 *
	 * @param integer $tipo
	 */
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Get tipo
	 *
	 * @return integer 
	 */
	public function getTipo() {
		return $this->tipo;
	}

	public function __toString() {
		return $this->getNombre() . '--' . $this->getTipo();
	}
}