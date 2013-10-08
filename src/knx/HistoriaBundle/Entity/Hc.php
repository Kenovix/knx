<?php

namespace knx\HistoriaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Tests\Component\Translation\String;
use Doctrine\ORM\Mapping as ORM;

/**
 *  
 * knx\HistoriaBundle\Entity\Hc
 *
 * @ORM\Table(name="hc")
 * @ORM\Entity
 * 
 * @ORM\Entity(repositoryClass="knx\HistoriaBundle\Entity\Repository\HcRepository")
 */
class Hc {
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @var datetime $fechaIngre
	 *
	 * @ORM\Column(name="fecha_ingre", type="datetime", nullable=false)
	 */
	private $fechaIngre;

	/**
	 * @var datetime $fechaEgre
	 *
	 * @ORM\Column(name="fecha_egre", type="datetime", nullable=true)
	 */
	private $fechaEgre;	

	/**
	 * @var string $serviEgre
	 *
	 * @ORM\Column(name="servi_egre", type="integer", nullable=true)
	 */
	private $serviEgre;

	/**
	 * @var text $motivo
	 *
	 * @ORM\Column(name="motivo", type="text", nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 */
	private $motivo;

	/**
	 * @var text $estadoGen
	 *
	 * @ORM\Column(name="estado_gen", type="text", nullable=true)
	 */
	private $estadoGen;

	/**
	 * @var text $enfermedad
	 *
	 * @ORM\Column(name="enfermedad", type="text", nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 */
	private $enfermedad;

	/**
	 * @var integer $causaExt
	 *
	 * @ORM\Column(name="causaExt", type="integer", nullable=true)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 * @Assert\Choice(choices = {"1", "2", "3","4", "5", "6","7", "8", "9","10", "11", "12","13", "14", "15"}, message = "Selecciona una opción valida.")
	 */ 
	/*
	 * 01 = Accidente  de trabajo
	 * 02 = Accidente  de tránsito
	 * 03 = Accidente rábico
	 * 04 = Accidente ofídico
	 * 05 = Otro tipo de accidente
	 * 06 = Evento catastrófico
	 * 07 = Lesión por agresión
	 * 08 = Lesión auto infligida
	 * 09 = Sospecha de maltrato físico
	 * 10 = Sospecha de abuso sexual
	 * 11 = Sospecha de violencia sexual
	 * 12 = Sospecha de maltrato emocional
	 * 13 = Enfermedad general
	 * 14 = Enfermedad profesional
	 * 15 = Otra
	 */
	private $causaExt;

	/**
	 * @var string $tipoAtencion
	 *
	 * @ORM\Column(name="tipo_atencion", type="string", length=30, nullable=true)
	 * @Assert\Length(max=30)
	 */
	private $tipoAtencion;

	/**
	 * @var text $antecedentesGenerales
	 *
	 * @ORM\Column(name="antecedentes_generales", type="text", nullable=true)
	 */
	private $antecedentesGenerales;

	/**
	 * @var text $antecedentesFisio
	 *
	 * @ORM\Column(name="antecedentes_fisio", type="text", nullable=true)
	 */
	private $antecedentesFisio;

	/**
	 * @var text $antecedentesGine
	 *
	 * @ORM\Column(name="antecedentes_gine", type="text", nullable=true)
	 */
	private $antecedentesGine;

	/**
	 * @var text $antecedentesPatologicos
	 *
	 * @ORM\Column(name="antecedentes_patologicos", type="text", nullable=true)
	 */
	private $antecedentesPatologicos;

	/**
	 * @var text $habitosNocivos
	 *
	 * @ORM\Column(name="habitos_nocivos", type="text", nullable=true)
	 */
	private $habitosNocivos;

	/**
	 * @var text $inmunizaciones
	 *
	 * @ORM\Column(name="inmunizaciones", type="text", nullable=true)
	 */
	private $inmunizaciones;

	/**
	 * @var text $alergias
	 *
	 * @ORM\Column(name="alergias", type="text", nullable=true)
	 */
	private $alergias;

	/**
	 * @var text $antecedentesFami
	 *
	 * @ORM\Column(name="antecedentes_fami", type="text", nullable=true)
	 */
	private $antecedentesFami;

	/**
	 * @var text $cabeza
	 *
	 * @ORM\Column(name="cabeza", type="text", nullable=true)
	 */
	private $cabeza;

	/**
	 * @var text $cara
	 *
	 * @ORM\Column(name="cara", type="text", nullable=true)
	 */
	private $cara;

	/**
	 * @var text $ojos
	 *
	 * @ORM\Column(name="ojos", type="text", nullable=true)
	 */
	private $ojos;

	/**
	 * @var text $oidos
	 *
	 * @ORM\Column(name="oidos", type="text", nullable=true)
	 */
	private $oidos;

	/**
	 * @var text $nariz
	 *
	 * @ORM\Column(name="nariz", type="text", nullable=true)
	 */
	private $nariz;

	/**
	 * @var text $boca
	 *
	 * @ORM\Column(name="boca", type="text", nullable=true)
	 */
	private $boca;

	/**
	 * @var text $cuello
	 *
	 * @ORM\Column(name="cuello", type="text", nullable=true)
	 */
	private $cuello;

	/**
	 * @var text $torax
	 *
	 * @ORM\Column(name="torax", type="text", nullable=true)
	 */
	private $torax;

	/**
	 * @var text $pulmones
	 *
	 * @ORM\Column(name="pulmones", type="text", nullable=true)
	 */
	private $pulmones;

	/**
	 * @var text $abdomen
	 *
	 * @ORM\Column(name="abdomen", type="text", nullable=true)
	 */
	private $abdomen;

	/**
	 * @var text $espalda
	 *
	 * @ORM\Column(name="espalda", type="text", nullable=true)
	 */
	private $espalda;

	/**
	 * @var text $extremidades
	 *
	 * @ORM\Column(name="extremidades", type="text", nullable=true)
	 */
	private $extremidades;

	/**
	 * @var text $genitales
	 *
	 * @ORM\Column(name="genitales", type="text", nullable=true)
	 */
	private $genitales;

	/**
	 * @var string $tipoDx
	 *
	 * @ORM\Column(name="tipo_dx", type="string", length=1, nullable=true)
	 * @Assert\Length(max=1)
	 */
	private $tipoDx;

	/**
	 * @var text $conducta
	 *
	 * @ORM\Column(name="conducta", type="text", nullable=false)
	 * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
	 */
	private $conducta;

	/**
	 * @var text $evolucion
	 *
	 * @ORM\Column(name="evolucion", type="text", nullable=true)
	 */
	private $evolucion;

	/**
	 * @var text $dxSalida
	 *
	 * @ORM\Column(name="dx_salida", type="integer", nullable=true)
	 */
	private $dxSalida;

	/**
	 * @var text $condSalida
	 *
	 * @ORM\Column(name="cond_salida", type="text", nullable=true)
	 */
	private $condSalida;

	/**
	 * @var text $manejoSalida
	 *
	 * @ORM\Column(name="manejo_salida", type="text", nullable=true)
	 */
	private $manejoSalida;

	/**
	 * @var text $destino
	 *
	 * @ORM\Column(name="destino", type="string", length=20, nullable=true)
	 * @Assert\Length(max=20)
	 */
	private $destino;
	
	/**
	 * @var string $destino_r
	 *
	 * @ORM\Column(name="destino_r", type="string", length=50, nullable=true)	
	 * @Assert\Length(max=50)
	 */	
	private $destino_r;
	/**
	 * @var string $especialidad_r
	 *
	 * @ORM\Column(name="especialidad_r", type="string", length=50, nullable=true)
	 * @Assert\Length(max=50)
	 */
	private $especialidad_r;
	/**
	 * @var string $nuAuto_r
	 *
	 * @ORM\Column(name="nuAuto_r", type="string", length=10, nullable=true)
	 * @Assert\Length(max=10)
	 */	
	private $nuAuto_r;
	/**
	 * @var text $descripcion_r
	 *
	 * @ORM\Column(name="descripcion_r", type="text", nullable=true)	 
	 */
	private $descripcion_r;	
	/**
	 * @var string $rServicio
	 *
	 * @ORM\Column(name="rServicio", type="string", length=15, nullable=true)
	 * @Assert\Length(max=15)
	 */
	private $rServicio;	
	
	/**
	 * @var string $o_sentidos
	 *
	 * @ORM\Column(name="o_sentidos", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $o_sentidos;
	/**
	 * @var string $a_respiratorio
	 *
	 * @ORM\Column(name="a_respiratorio", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $a_respiratorio;
	/**
	 * @var string $a_cardiovascular
	 *
	 * @ORM\Column(name="a_cardiovascular", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $a_cardiovascular;
	/**
	 * @var string $a_digestivo
	 *
	 * @ORM\Column(name="a_digestivo", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $a_digestivo;
	/**
	 * @var string $a_genitoUrinario
	 *
	 * @ORM\Column(name="a_genitoUrinario", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $a_genitoUrinario;
	/**
	 * @var string $a_hematologico
	 *
	 * @ORM\Column(name="a_hematologico", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $a_hematologico;
	/**
	 * @var string $s_osteoarticular
	 *
	 * @ORM\Column(name="s_osteoarticular", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $s_osteoarticular;
	/**
	 * @var string $s_nervioso
	 *
	 * @ORM\Column(name="s_nervioso", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $s_nervioso;
	/**
	 * @var string $s_endocrino
	 *
	 * @ORM\Column(name="s_endocrino", type="string", length=200, nullable=true)
	 * @Assert\Length(max=200)
	 */
	private $s_endocrino;
	
	/**
	 * @var text $medicamentos_s
	 *
	 * @ORM\Column(name="medicamentos_s", type="text", nullable=true)
	 */
	private $medicamentos_s;
	/**
	 * @var text $procedimientos_s
	 *
	 * @ORM\Column(name="procedimientos_s", type="text", nullable=true)
	 */
	private $procedimientos_s;
	/**
	 * @var text $examenes_s
	 *
	 * @ORM\Column(name="examenes_s", type="text", nullable=true)
	 */
	private $examenes_s;
	
	/**
	 * @var text $incapacidad
	 *
	 * @ORM\Column(name="incapacidad", type="text", nullable=true)
	 */
	private $incapacidad;
	
	/**
	 * @var datetime $pFechaN
	 *
	 * @ORM\Column(name="p_fecha_n", type="datetime", nullable=true)
	 */
	private $pFechaN;//parto_fecha_nacimiento
	
	/**
	 * @var integer $pEdadG
	 *
	 * @ORM\Column(name="p_edad_g", type="integer", nullable=true)
	 * @Assert\Range(
     *      min = "1",
     *      max = "36",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 36"
     * ))
	 */
	private $pEdadG;//parto_edad_gestacional
	
	/**
	 * @var string $pControlP
	 *
	 * @ORM\Column(name="p_control_p", type="string", length=2, nullable=true)	 
	 * @Assert\Choice(choices = {"SI","NO"}, message = "Selecciona una opción valida.")	 
	 */
	private $pControlP; //parto_control_prenatal
	
	/**
	 * @var string $pSexo
	 *
	 * @ORM\Column(name="p_sexo", type="string", length=1, nullable=true)	 
	 * @Assert\Choice(choices = {"M", "F"}, message = "Selecciona una opción valida.")
	 */
	private $pSexo;//parto_sexo
	
	/**
	 * @var integer $pPeso
	 *
	 * @ORM\Column(name="p_peso", type="integer", nullable=true)
	 * @Assert\Range(
     *      min = "10",
     *      max = "16000",
     *      minMessage = "El menor número a ingresar es 10",
     *      maxMessage = "El mayor número a ingresar es 16000"
     * ))
	 */
	private $pPeso;//parto_peso
	
	/**
	 * @var text $pCausaM
	 *
	 * @ORM\Column(name="p_causa_m", type="text", nullable=true)
	 */
	private $pCausaM;//parto_causa_muerte
	
	/**
	 * @var datetime $pFechaM
	 *
	 * @ORM\Column(name="p_fecha_m", type="datetime", nullable=true)
	 */
	private $pFechaM;//parto_fecha_muerte
	
	/**
	 * @var text $pDx
	 *
	 * @ORM\Column(name="p_diagnostico", type="integer", nullable=true)
	 */
	private $pDx;//parto_diagnostico
	
	
	

	/**
	 * @ORM\OneToOne(targetEntity="knx\FacturacionBundle\Entity\Factura", inversedBy="hc")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="factura_id", referencedColumnName="id" )
	 * })          
	 */ 

	private $factura;

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
	 * Set fechaIngre
	 *
	 * @param \DateTime $fechaIngre
	 * @return Hc
	 */
	public function setFechaIngre($fechaIngre) {
		$this->fechaIngre = $fechaIngre;

		return $this;
	}

	/**
	 * Get fechaIngre
	 *
	 * @return \DateTime 
	 */
	public function getFechaIngre() {
		return $this->fechaIngre;
	}

	/**
	 * Set fechaEgre
	 *
	 * @param \DateTime $fechaEgre
	 * @return Hc
	 */
	public function setFechaEgre($fechaEgre) {
		$this->fechaEgre = $fechaEgre;

		return $this;
	}

	/**
	 * Get fechaEgre
	 *
	 * @return \DateTime 
	 */
	public function getFechaEgre() {
		return $this->fechaEgre;
	}

	/**
	 * Set serviEgre
	 *
	 * @param integer $serviEgre
	 * @return Hc
	 */
	public function setServiEgre($serviEgre) {
		$this->serviEgre = $serviEgre;

		return $this;
	}

	/**
	 * Get serviEgre
	 *
	 * @return integer 
	 */
	public function getServiEgre() {
		return $this->serviEgre;
	}

	/**
	 * Set motivo
	 *
	 * @param string $motivo
	 * @return Hc
	 */
	public function setMotivo($motivo) {
		$this->motivo = $motivo;

		return $this;
	}

	/**
	 * Get motivo
	 *
	 * @return string 
	 */
	public function getMotivo() {
		return $this->motivo;
	}

	/**
	 * Set estadoGen
	 *
	 * @param string $estadoGen
	 * @return Hc
	 */
	public function setEstadoGen($estadoGen) {
		$this->estadoGen = $estadoGen;

		return $this;
	}

	/**
	 * Get estadoGen
	 *
	 * @return string 
	 */
	public function getEstadoGen() {
		return $this->estadoGen;
	}

	/**
	 * Set enfermedad
	 *
	 * @param string $enfermedad
	 * @return Hc
	 */
	public function setEnfermedad($enfermedad) {
		$this->enfermedad = $enfermedad;

		return $this;
	}

	/**
	 * Get enfermedad
	 *
	 * @return string 
	 */
	public function getEnfermedad() {
		return $this->enfermedad;
	}

	/**
	 * Set causaExt
	 *
	 * @param integer $causaExt
	 * @return Hc
	 */
	public function setCausaExt($causaExt) {
		$this->causaExt = $causaExt;

		return $this;
	}

	/**
	 * Get causaExt
	 *
	 * @return integer 
	 */
	public function getCausaExt() {
		return $this->causaExt;
	}

	/**
	 * Set tipoAtencion
	 *
	 * @param string $tipoAtencion
	 * @return Hc
	 */
	public function setTipoAtencion($tipoAtencion) {
		$this->tipoAtencion = $tipoAtencion;

		return $this;
	}

	/**
	 * Get tipoAtencion
	 *
	 * @return string 
	 */
	public function getTipoAtencion() {
		return $this->tipoAtencion;
	}

	/**
	 * Set antecedentesGenerales
	 *
	 * @param string $antecedentesGenerales
	 * @return Hc
	 */
	public function setAntecedentesGenerales($antecedentesGenerales) {
		$this->antecedentesGenerales = $antecedentesGenerales;

		return $this;
	}

	/**
	 * Get antecedentesGenerales
	 *
	 * @return string 
	 */
	public function getAntecedentesGenerales() {
		return $this->antecedentesGenerales;
	}

	/**
	 * Set antecedentesFisio
	 *
	 * @param string $antecedentesFisio
	 * @return Hc
	 */
	public function setAntecedentesFisio($antecedentesFisio) {
		$this->antecedentesFisio = $antecedentesFisio;

		return $this;
	}

	/**
	 * Get antecedentesFisio
	 *
	 * @return string 
	 */
	public function getAntecedentesFisio() {
		return $this->antecedentesFisio;
	}

	/**
	 * Set antecedentesGine
	 *
	 * @param string $antecedentesGine
	 * @return Hc
	 */
	public function setAntecedentesGine($antecedentesGine) {
		$this->antecedentesGine = $antecedentesGine;

		return $this;
	}

	/**
	 * Get antecedentesGine
	 *
	 * @return string 
	 */
	public function getAntecedentesGine() {
		return $this->antecedentesGine;
	}

	/**
	 * Set antecedentesPatologicos
	 *
	 * @param string $antecedentesPatologicos
	 * @return Hc
	 */
	public function setAntecedentesPatologicos($antecedentesPatologicos) {
		$this->antecedentesPatologicos = $antecedentesPatologicos;

		return $this;
	}

	/**
	 * Get antecedentesPatologicos
	 *
	 * @return string 
	 */
	public function getAntecedentesPatologicos() {
		return $this->antecedentesPatologicos;
	}

	/**
	 * Set habitos_nocivos
	 *
	 * @param string $habitosNocivos
	 * @return Hc
	 */
	public function setHabitosNocivos($habitosNocivos) {
		$this->habitosNocivos = $habitosNocivos;

		return $this;
	}

	/**
	 * Get habitos_nocivos
	 *
	 * @return string 
	 */
	public function getHabitosNocivos() {
		return $this->habitosNocivos;
	}

	/**
	 * Set inmunizaciones
	 *
	 * @param string $inmunizaciones
	 * @return Hc
	 */
	public function setInmunizaciones($inmunizaciones) {
		$this->inmunizaciones = $inmunizaciones;

		return $this;
	}

	/**
	 * Get inmunizaciones
	 *
	 * @return string 
	 */
	public function getInmunizaciones() {
		return $this->inmunizaciones;
	}

	/**
	 * Set alergias
	 *
	 * @param string $alergias
	 * @return Hc
	 */
	public function setAlergias($alergias) {
		$this->alergias = $alergias;

		return $this;
	}

	/**
	 * Get alergias
	 *
	 * @return string 
	 */
	public function getAlergias() {
		return $this->alergias;
	}

	/**
	 * Set antecedentesFami
	 *
	 * @param string $antecedentesFami
	 * @return Hc
	 */
	public function setAntecedentesFami($antecedentesFami) {
		$this->antecedentesFami = $antecedentesFami;

		return $this;
	}

	/**
	 * Get antecedentesFami
	 *
	 * @return string 
	 */
	public function getAntecedentesFami() {
		return $this->antecedentesFami;
	}

	/**
	 * Set cabeza
	 *
	 * @param string $cabeza
	 * @return Hc
	 */
	public function setCabeza($cabeza) {
		$this->cabeza = $cabeza;

		return $this;
	}

	/**
	 * Get cabeza
	 *
	 * @return string 
	 */
	public function getCabeza() {
		return $this->cabeza;
	}

	/**
	 * Set cara
	 *
	 * @param string $cara
	 * @return Hc
	 */
	public function setCara($cara) {
		$this->cara = $cara;

		return $this;
	}

	/**
	 * Get cara
	 *
	 * @return string 
	 */
	public function getCara() {
		return $this->cara;
	}

	/**
	 * Set ojos
	 *
	 * @param string $ojos
	 * @return Hc
	 */
	public function setOjos($ojos) {
		$this->ojos = $ojos;

		return $this;
	}

	/**
	 * Get ojos
	 *
	 * @return string 
	 */
	public function getOjos() {
		return $this->ojos;
	}

	/**
	 * Set oidos
	 *
	 * @param string $oidos
	 * @return Hc
	 */
	public function setOidos($oidos) {
		$this->oidos = $oidos;

		return $this;
	}

	/**
	 * Get oidos
	 *
	 * @return string 
	 */
	public function getOidos() {
		return $this->oidos;
	}

	/**
	 * Set nariz
	 *
	 * @param string $nariz
	 * @return Hc
	 */
	public function setNariz($nariz) {
		$this->nariz = $nariz;

		return $this;
	}

	/**
	 * Get nariz
	 *
	 * @return string 
	 */
	public function getNariz() {
		return $this->nariz;
	}

	/**
	 * Set boca
	 *
	 * @param string $boca
	 * @return Hc
	 */
	public function setBoca($boca) {
		$this->boca = $boca;

		return $this;
	}

	/**
	 * Get boca
	 *
	 * @return string 
	 */
	public function getBoca() {
		return $this->boca;
	}

	/**
	 * Set cuello
	 *
	 * @param string $cuello
	 * @return Hc
	 */
	public function setCuello($cuello) {
		$this->cuello = $cuello;

		return $this;
	}

	/**
	 * Get cuello
	 *
	 * @return string 
	 */
	public function getCuello() {
		return $this->cuello;
	}

	/**
	 * Set torax
	 *
	 * @param string $torax
	 * @return Hc
	 */
	public function setTorax($torax) {
		$this->torax = $torax;

		return $this;
	}

	/**
	 * Get torax
	 *
	 * @return string 
	 */
	public function getTorax() {
		return $this->torax;
	}

	/**
	 * Set pulmones
	 *
	 * @param string $pulmones
	 * @return Hc
	 */
	public function setPulmones($pulmones) {
		$this->pulmones = $pulmones;

		return $this;
	}

	/**
	 * Get pulmones
	 *
	 * @return string 
	 */
	public function getPulmones() {
		return $this->pulmones;
	}

	/**
	 * Set abdomen
	 *
	 * @param string $abdomen
	 * @return Hc
	 */
	public function setAbdomen($abdomen) {
		$this->abdomen = $abdomen;

		return $this;
	}

	/**
	 * Get abdomen
	 *
	 * @return string 
	 */
	public function getAbdomen() {
		return $this->abdomen;
	}

	/**
	 * Set espalda
	 *
	 * @param string $espalda
	 * @return Hc
	 */
	public function setEspalda($espalda) {
		$this->espalda = $espalda;

		return $this;
	}

	/**
	 * Get espalda
	 *
	 * @return string 
	 */
	public function getEspalda() {
		return $this->espalda;
	}

	/**
	 * Set extremidades
	 *
	 * @param string $extremidades
	 * @return Hc
	 */
	public function setExtremidades($extremidades) {
		$this->extremidades = $extremidades;

		return $this;
	}

	/**
	 * Get extremidades
	 *
	 * @return string 
	 */
	public function getExtremidades() {
		return $this->extremidades;
	}

	/**
	 * Set genitales
	 *
	 * @param string $genitales
	 * @return Hc
	 */
	public function setGenitales($genitales) {
		$this->genitales = $genitales;

		return $this;
	}

	/**
	 * Get genitales
	 *
	 * @return string 
	 */
	public function getGenitales() {
		return $this->genitales;
	}

	/**
	 * Set tipoDx
	 *
	 * @param string $tipoDx
	 * @return Hc
	 */
	public function setTipoDx($tipoDx) {
		$this->tipoDx = $tipoDx;

		return $this;
	}

	/**
	 * Get tipoDx
	 *
	 * @return string 
	 */
	public function getTipoDx() {
		return $this->tipoDx;
	}

	/**
	 * Set conducta
	 *
	 * @param string $conducta
	 * @return Hc
	 */
	public function setConducta($conducta) {
		$this->conducta = $conducta;

		return $this;
	}

	/**
	 * Get conducta
	 *
	 * @return string 
	 */
	public function getConducta() {
		return $this->conducta;
	}

	/**
	 * Set evolucion
	 *
	 * @param string $evolucion
	 * @return Hc
	 */
	public function setEvolucion($evolucion) {
		$this->evolucion = $evolucion;

		return $this;
	}

	/**
	 * Get evolucion
	 *
	 * @return string 
	 */
	public function getEvolucion() {
		return $this->evolucion;
	}

	/**
	 * Set dxSalida
	 *
	 * @param string $dxSalida
	 * @return Hc
	 */
	public function setDxSalida($dxSalida) {
		$this->dxSalida = $dxSalida;

		return $this;
	}

	/**
	 * Get dxSalida
	 *
	 * @return string 
	 */
	public function getDxSalida() {
		return $this->dxSalida;
	}

	/**
	 * Set condSalida
	 *
	 * @param string $condSalida
	 * @return Hc
	 */
	public function setCondSalida($condSalida) {
		$this->condSalida = $condSalida;

		return $this;
	}

	/**
	 * Get condSalida
	 *
	 * @return string 
	 */
	public function getCondSalida() {
		return $this->condSalida;
	}

	/**
	 * Set manejoSalida
	 *
	 * @param string $manejoSalida
	 * @return Hc
	 */
	public function setManejoSalida($manejoSalida) {
		$this->manejoSalida = $manejoSalida;

		return $this;
	}

	/**
	 * Get manejoSalida
	 *
	 * @return string 
	 */
	public function getManejoSalida() {
		return $this->manejoSalida;
	}

	/**
	 * Set destino
	 *
	 * @param string $destino
	 * @return Hc
	 */
	public function setDestino($destino) {
		$this->destino = $destino;

		return $this;
	}

	/**
	 * Get destino
	 *
	 * @return string 
	 */
	public function getDestino() {
		return $this->destino;
	}

	/**
	 * Set created
	 *
	 * @param \DateTime $created
	 * @return Hc
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
	 * @return Hc
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
	 * Set factura
	 *
	 * @param \knx\FacturacionBundle\Entity\Factura $factura
	 * @return Hc
	 */
	public function setFactura(\knx\FacturacionBundle\Entity\Factura $factura) {
		$this->factura = $factura;

		return $this;
	}

	/**
	 * Get factura
	 *
	 * @return \knx\FacturacionBundle\Entity\Factura 
	 */
	public function getFactura() {
		return $this->factura;
	}

    /**
     * Set destino_r
     *
     * @param string $destinoR
     * @return Hc
     */
    public function setDestinoR($destinoR)
    {
        $this->destino_r = $destinoR;
    
        return $this;
    }

    /**
     * Get destino_r
     *
     * @return string 
     */
    public function getDestinoR()
    {
        return $this->destino_r;
    }

    /**
     * Set especialidad_r
     *
     * @param string $especialidadR
     * @return Hc
     */
    public function setEspecialidadR($especialidadR)
    {
        $this->especialidad_r = $especialidadR;
    
        return $this;
    }

    /**
     * Get especialidad_r
     *
     * @return string 
     */
    public function getEspecialidadR()
    {
        return $this->especialidad_r;
    }

    /**
     * Set nuAuto_r
     *
     * @param string $nuAutoR
     * @return Hc
     */
    public function setNuAutoR($nuAutoR)
    {
        $this->nuAuto_r = $nuAutoR;
    
        return $this;
    }

    /**
     * Get nuAuto_r
     *
     * @return string 
     */
    public function getNuAutoR()
    {
        return $this->nuAuto_r;
    }

    /**
     * Set descripcion_r
     *
     * @param string $descripcionR
     * @return Hc
     */
    public function setDescripcionR($descripcionR)
    {
        $this->descripcion_r = $descripcionR;
    
        return $this;
    }

    /**
     * Get descripcion_r
     *
     * @return string 
     */
    public function getDescripcionR()
    {
        return $this->descripcion_r;
    }
    
    

    /**
     * Set o_sentidos
     *
     * @param string $oSentidos
     * @return Hc
     */
    public function setOSentidos($oSentidos)
    {
        $this->o_sentidos = $oSentidos;
    
        return $this;
    }

    /**
     * Get o_sentidos
     *
     * @return string 
     */
    public function getOSentidos()
    {
        return $this->o_sentidos;
    }

    /**
     * Set a_respiratorio
     *
     * @param string $aRespiratorio
     * @return Hc
     */
    public function setARespiratorio($aRespiratorio)
    {
        $this->a_respiratorio = $aRespiratorio;
    
        return $this;
    }

    /**
     * Get a_respiratorio
     *
     * @return string 
     */
    public function getARespiratorio()
    {
        return $this->a_respiratorio;
    }

    /**
     * Set a_cardiovascular
     *
     * @param string $aCardiovascular
     * @return Hc
     */
    public function setACardiovascular($aCardiovascular)
    {
        $this->a_cardiovascular = $aCardiovascular;
    
        return $this;
    }

    /**
     * Get a_cardiovascular
     *
     * @return string 
     */
    public function getACardiovascular()
    {
        return $this->a_cardiovascular;
    }

    /**
     * Set a_digestivo
     *
     * @param string $aDigestivo
     * @return Hc
     */
    public function setADigestivo($aDigestivo)
    {
        $this->a_digestivo = $aDigestivo;
    
        return $this;
    }

    /**
     * Get a_digestivo
     *
     * @return string 
     */
    public function getADigestivo()
    {
        return $this->a_digestivo;
    }

    /**
     * Set a_genitoUrinario
     *
     * @param string $aGenitoUrinario
     * @return Hc
     */
    public function setAGenitoUrinario($aGenitoUrinario)
    {
        $this->a_genitoUrinario = $aGenitoUrinario;
    
        return $this;
    }

    /**
     * Get a_genitoUrinario
     *
     * @return string 
     */
    public function getAGenitoUrinario()
    {
        return $this->a_genitoUrinario;
    }

    /**
     * Set a_hematologico
     *
     * @param string $aHematologico
     * @return Hc
     */
    public function setAHematologico($aHematologico)
    {
        $this->a_hematologico = $aHematologico;
    
        return $this;
    }

    /**
     * Get a_hematologico
     *
     * @return string 
     */
    public function getAHematologico()
    {
        return $this->a_hematologico;
    }

    /**
     * Set s_osteoarticular
     *
     * @param string $sOsteoarticular
     * @return Hc
     */
    public function setSOsteoarticular($sOsteoarticular)
    {
        $this->s_osteoarticular = $sOsteoarticular;
    
        return $this;
    }

    /**
     * Get s_osteoarticular
     *
     * @return string 
     */
    public function getSOsteoarticular()
    {
        return $this->s_osteoarticular;
    }

    /**
     * Set s_nervioso
     *
     * @param string $sNervioso
     * @return Hc
     */
    public function setSNervioso($sNervioso)
    {
        $this->s_nervioso = $sNervioso;
    
        return $this;
    }

    /**
     * Get s_nervioso
     *
     * @return string 
     */
    public function getSNervioso()
    {
        return $this->s_nervioso;
    }

    /**
     * Set s_endocrino
     *
     * @param string $sEndocrino
     * @return Hc
     */
    public function setSEndocrino($sEndocrino)
    {
        $this->s_endocrino = $sEndocrino;
    
        return $this;
    }

    /**
     * Get s_endocrino
     *
     * @return string 
     */
    public function getSEndocrino()
    {
        return $this->s_endocrino;
    }

    /**
     * Set medicamentos_s
     *
     * @param string $medicamentosS
     * @return Hc
     */
    public function setMedicamentosS($medicamentosS)
    {
        $this->medicamentos_s = $medicamentosS;
    
        return $this;
    }

    /**
     * Get medicamentos_s
     *
     * @return string 
     */
    public function getMedicamentosS()
    {
        return $this->medicamentos_s;
    }

    /**
     * Set procedimientos_s
     *
     * @param string $procedimientosS
     * @return Hc
     */
    public function setProcedimientosS($procedimientosS)
    {
        $this->procedimientos_s = $procedimientosS;
    
        return $this;
    }

    /**
     * Get procedimientos_s
     *
     * @return string 
     */
    public function getProcedimientosS()
    {
        return $this->procedimientos_s;
    }

    /**
     * Set examenes_s
     *
     * @param string $examenesS
     * @return Hc
     */
    public function setExamenesS($examenesS)
    {
        $this->examenes_s = $examenesS;
    
        return $this;
    }

    /**
     * Get examenes_s
     *
     * @return string 
     */
    public function getExamenesS()
    {
        return $this->examenes_s;
    }
    
    
    /**
     * Set incapacidad
     *
     * @param string $incapacidad
     * @return Hc
     */
    public function setIncapacidad($incapacidad)
    {
    	$this->incapacidad = $incapacidad;
    
    	return $this;
    }
    
    /**
     * Get incapacidad
     *
     * @return string
     */
    public function getIncapacidad()
    {
    	return $this->incapacidad;
    }

    /**
     * Set rServicio
     *
     * @param string $rServicio
     * @return Hc
     */
    public function setRServicio($rServicio)
    {
        $this->rServicio = $rServicio;
    
        return $this;
    }

    /**
     * Get rServicio
     *
     * @return string 
     */
    public function getRServicio()
    {
        return $this->rServicio;
    }

    /**
     * Set pFechaN
     *
     * @param \DateTime $pFechaN
     * @return Hc
     */
    public function setPFechaN($pFechaN)
    {
        $this->pFechaN = $pFechaN;
    
        return $this;
    }

    /**
     * Get pFechaN
     *
     * @return \DateTime 
     */
    public function getPFechaN()
    {
        return $this->pFechaN;
    }

    /**
     * Set pEdadG
     *
     * @param integer $pEdadG
     * @return Hc
     */
    public function setPEdadG($pEdadG)
    {
        $this->pEdadG = $pEdadG;
    
        return $this;
    }

    /**
     * Get pEdadG
     *
     * @return integer 
     */
    public function getPEdadG()
    {
        return $this->pEdadG;
    }

    /**
     * Set pControlP
     *
     * @param string $pControlP
     * @return Hc
     */
    public function setPControlP($pControlP)
    {
        $this->pControlP = $pControlP;
    
        return $this;
    }

    /**
     * Get pControlP
     *
     * @return string 
     */
    public function getPControlP()
    {
        return $this->pControlP;
    }

    /**
     * Set pSexo
     *
     * @param string $pSexo
     * @return Hc
     */
    public function setPSexo($pSexo)
    {
        $this->pSexo = $pSexo;
    
        return $this;
    }

    /**
     * Get pSexo
     *
     * @return string 
     */
    public function getPSexo()
    {
        return $this->pSexo;
    }

    /**
     * Set pPeso
     *
     * @param integer $pPeso
     * @return Hc
     */
    public function setPPeso($pPeso)
    {
        $this->pPeso = $pPeso;
    
        return $this;
    }

    /**
     * Get pPeso
     *
     * @return integer 
     */
    public function getPPeso()
    {
        return $this->pPeso;
    }

    /**
     * Set pCausaM
     *
     * @param string $pCausaM
     * @return Hc
     */
    public function setPCausaM($pCausaM)
    {
        $this->pCausaM = $pCausaM;
    
        return $this;
    }

    /**
     * Get pCausaM
     *
     * @return string 
     */
    public function getPCausaM()
    {
        return $this->pCausaM;
    }

    /**
     * Set pFechaM
     *
     * @param \DateTime $pFechaM
     * @return Hc
     */
    public function setPFechaM($pFechaM)
    {
        $this->pFechaM = $pFechaM;
    
        return $this;
    }

    /**
     * Get pFechaM
     *
     * @return \DateTime 
     */
    public function getPFechaM()
    {
        return $this->pFechaM;
    }

    /**
     * Set pDx
     *
     * @param integer $pDx
     * @return Hc
     */
    public function setPDx($pDx)
    {
        $this->pDx = $pDx;
    
        return $this;
    }

    /**
     * Get pDx
     *
     * @return integer 
     */
    public function getPDx()
    {
        return $this->pDx;
    }
}