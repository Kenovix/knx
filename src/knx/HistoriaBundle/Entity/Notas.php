<?php

namespace knx\HistoriaBundle\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HistoriaBundle\Entity\Notas
 *
 * @ORM\Table(name="notas")
 * @ORM\Entity
 * 
 * @ORM\Entity(repositoryClass="knx\HistoriaBundle\Entity\Repository\NotasRepository")
 */
class Notas {
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
	 * @Assert\DateTime(message="Por favor ingresa fecha valida")
	 */
	private $fecha;

	/**
	 * @var text $resumenNota
	 *
	 * @ORM\Column(name="resumenNota", type="text", nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 */
	private $resumenNota;

	/**
	 * @var integer $temp
	 *
	 * @ORM\Column(name="temp", type="integer", nullable=true)	 
	 * @Assert\Range(
     *      min = "1",
     *      max = "99",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 99"
     * )	 
	 */
	private $temp;

	/**
	 * @var integer $pulso
	 *
	 * @ORM\Column(name="pulso", type="integer", nullable=true)	 
	 * @Assert\Range(
     *      min = "1",
     *      max = "199",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 199"
     * )	
	 */
	private $pulso;

	/**
	 * @var integer $fC
	 *
	 * @ORM\Column(name="f_c", type="integer", nullable=true)	 
	 * @Assert\Range(
     *      min = "1",
     *      max = "199",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 199"
     * )	 
	 */
	private $fC;

	/**
	 * @var integer $fR
	 *
	 * @ORM\Column(name="f_r", type="integer", nullable=true)	 
	 * @Assert\Range(
     *      min = "1",
     *      max = "99",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 99"
     * )
	 */
	private $fR;

	/**
	 * @var string $ta
	 *
	 * @ORM\Column(name="ta", type="string", length=7, nullable=false)
	 * @Assert\Length(max=7)	 
	 */
	private $ta;

	/**
	 * @var integer $peso
	 *
	 * @ORM\Column(name="peso", type="integer", nullable=true)	 
	 * @Assert\Range(
     *      min = "1",
     *      max = "499",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 499"
     * )	 
	 */
	private $peso;

	/**
	 * @var integer $estatura
	 *
	 * @ORM\Column(name="estatura", type="integer", nullable=true)	 
	 * @Assert\Range(
     *      min = "1",
     *      max = "399",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 399"
     * )
	 */
	private $estatura;

	/**
	 * @var string $glasgow
	 *
	 * @ORM\Column(name="glasgow", type="string", length=5, nullable=false)
	 * @Assert\Length(max=5)
	 */
	private $glasgow;

	/**
	 * @var integer $imc
	 *
	 * @ORM\Column(name="imc", type="integer")
	 * @Assert\Range(
     *      min = "1",
     *      max = "399",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 399"
     * )
	 *
	 */
	private $imc;
	
	/**
	 * @var string $triage
	 *
	 * @ORM\Column(name="triage", type="string", length=3, nullable=true)	
	 * @Assert\Length(max=3)
	 */
	private $triage;

	/**
	 * @var Hc
	 *
	 * @ORM\ManyToOne(targetEntity="knx\HistoriaBundle\Entity\Hc")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="hc_id", referencedColumnName="id")
	 * })
	 */
	private $hc;

	/**
	 * @var Responsable
	 *
	 * @ORM\ManyToOne(targetEntity="knx\UsuarioBundle\Entity\Usuario")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="responsable_id", referencedColumnName="id")
	 * })
	 */
	private $responsable;

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
	 * @return Notas
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
	 * Set resumenNota
	 *
	 * @param string $resumenNota
	 * @return Notas
	 */
	public function setResumenNota($resumenNota) {
		$this->resumenNota = $resumenNota;

		return $this;
	}

	/**
	 * Get resumenNota
	 *
	 * @return string 
	 */
	public function getResumenNota() {
		return $this->resumenNota;
	}

	/**
	 * Set temp
	 *
	 * @param integer $temp
	 * @return Hc
	 */
	public function setTemp($temp) {
		$this->temp = $temp;

		return $this;
	}

	/**
	 * Get temp
	 *
	 * @return integer
	 */
	public function getTemp() {
		return $this->temp;
	}

	/**
	 * Set pulso
	 *
	 * @param integer $pulso
	 * @return Hc
	 */
	public function setPulso($pulso) {
		$this->pulso = $pulso;

		return $this;
	}

	/**
	 * Get pulso
	 *
	 * @return integer
	 */
	public function getPulso() {
		return $this->pulso;
	}

	/**
	 * Set fC
	 *
	 * @param integer $fC
	 * @return Hc
	 */
	public function setFC($fC) {
		$this->fC = $fC;

		return $this;
	}

	/**
	 * Get fC
	 *
	 * @return integer
	 */
	public function getFC() {
		return $this->fC;
	}

	/**
	 * Set fR
	 *
	 * @param integer $fR
	 * @return Hc
	 */
	public function setFR($fR) {
		$this->fR = $fR;

		return $this;
	}

	/**
	 * Get fR
	 *
	 * @return integer
	 */
	public function getFR() {
		return $this->fR;
	}

	/**
	 * Set ta
	 *
	 * @param string $ta
	 * @return Hc
	 */
	public function setTa($ta) {
		$this->ta = $ta;

		return $this;
	}

	/**
	 * Get ta
	 *
	 * @return string
	 */
	public function getTa() {
		return $this->ta;
	}

	/**
	 * Set peso
	 *
	 * @param integer $peso
	 * @return Hc
	 */
	public function setPeso($peso) {
		$this->peso = $peso;

		return $this;
	}

	/**
	 * Get peso
	 *
	 * @return integer
	 */
	public function getPeso() {
		return $this->peso;
	}

	/**
	 * Set estatura
	 *
	 * @param integer $estatura
	 * @return Hc
	 */
	public function setEstatura($estatura) {
		$this->estatura = $estatura;

		return $this;
	}

	/**
	 * Get estatura
	 *
	 * @return integer
	 */
	public function getEstatura() {
		return $this->estatura;
	}

	/**
	 * Set glasgow
	 *
	 * @param string $glasgow
	 * @return Hc
	 */
	public function setGlasgow($glasgow) {
		$this->glasgow = $glasgow;

		return $this;
	}

	/**
	 * Get glasgow
	 *
	 * @return string
	 */
	public function getGlasgow() {
		return $this->glasgow;
	}

	/**
	 * Set imc
	 *
	 * @param integer $imc
	 * @return Hc
	 */
	public function setImc($imc) {
		$this->imc = $imc;

		return $this;
	}

	/**
	 * Get imc
	 *
	 * @return integer
	 */
	public function getImc() {
		return $this->imc;
	}
	
	/**
	 * Set triage
	 *
	 * @param string $triage
	 * @return Hc
	 */
	public function setTriage($triage) {
		$this->triage = $triage;
	
		return $this;
	}
	
	/**
	 * Get triage
	 *
	 * @return string
	 */
	public function getTriage() {
		return $this->triage;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 * @return Notas
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
	 * @return Notas
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
	 * Set Hc
	 *
	 * @param \knx\HistoriaBundle\Entity\Hc $hc
	 * @return Notas
	 */
	public function setHc(\knx\HistoriaBundle\Entity\Hc $hc) {
		$this->hc = $hc;

		return $this;
	}

	/**
	 * Get Hc
	 *
	 * @return \knx\HistoriaBundle\Entity\Hc 
	 */
	public function getHc() {
		return $this->hc;
	}

	/**
	 * Set responsable
	 *
	 * @param \knx\UsuarioBundle\Entity\Usuario $responsable
	 * @return Notas
	 */
	public function setResponsable(
			\knx\UsuarioBundle\Entity\Usuario $responsable) {
		$this->responsable = $responsable;

		return $this;
	}

	/**
	 * Get responsable
	 *
	 * @return \knx\UsuarioBundle\Entity\Usuario 
	 */
	public function getResponsable() {
		return $this->responsable;
	}
}