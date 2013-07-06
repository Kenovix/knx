<?php

namespace knx\HistoriaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HistoriaBundle\Entity\Cie
 *
 * @ORM\Table(name="cie")
 * @ORM\Entity
 * 
 */
class Cie {
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
	 * @ORM\Column(name="codigo", type="string", length=5, nullable=false)
	 */
	private $codigo;

	/**
	 * @var string $nombre
	 *
	 * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
	 */
	private $nombre;

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
	 * @param string $codigo
	 * @return Cie
	 */
	public function setCodigo($codigo) {
		$this->codigo = $codigo;

		return $this;
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
	 * @return Cie
	 */
	public function setNombre($nombre) {
		$this->nombre = $nombre;

		return $this;
	}

	/**
	 * Get nombre
	 *
	 * @return string 
	 */
	public function getNombre() {
		return $this->nombre;
	}

	public function __toString() {
		return $this->getNombre();
	}
}