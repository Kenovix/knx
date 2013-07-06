<?php

namespace knx\HistoriaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HistoriaBundle\Entity\MedicamentoHistoria
 *
 * @ORM\Table(name="medicamento_historia")
 * @ORM\Entity
 * 
 * @ORM\Entity(repositoryClass="knx\HistoriaBundle\Entity\Repository\MedicamentoRepository")
 */
class MedicamentoHistoria {

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="knx\HistoriaBundle\Entity\Hc")
	 */
	private $hc;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="knx\HistoriaBundle\Entity\Medicamento")
	 */
	private $medicamento;
	/**
	 * @var boolean $estado
	 *
	 * @ORM\Column(name="estado", type="string", length=1, nullable=false)
	 */
	private $estado;

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
	 * @return boolean 
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
	 * Set medicamento
	 *
	 * @param knx\HistoriaBundle\Entity\Medicamento $medicamento
	 */
	public function setMedicamento(
			\knx\HistoriaBundle\Entity\Medicamento $medicamento) {
		$this->medicamento = $medicamento;
	}

	/**
	 * Get medicamento
	 *
	 * @return knx\HistoriaBundle\Entity\medicamento 
	 */
	public function getMedicamento() {
		return $this->medicamento;
	}
}