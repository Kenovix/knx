<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * knx\FarmaciaBundle\Entity\Imv
 *
 * @ORM\Table(name="imv")
 * @DoctrineAssert\UniqueEntity("codCups")
 * @DoctrineAssert\UniqueEntity("codAdmin")
 * @DoctrineAssert\UniqueEntity("nombre")
 * @ORM\Entity
 * 
 */
class Imv
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
     * @var string $codCups
     * 
     * @ORM\Column(name="cod_cups", type="string", length=100, nullable=false, unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Range(min=1,max=999999)     
     */
    private $codCups;
    
    /**
     * @var string $codAdmin
     * 
     * @ORM\Column(name="cod_admin", type="string", length=100, nullable=true, unique=true)
     * 
     */
    private $codAdmin;
    
    /**
     * @var string $cums
     * 
     * @ORM\Column(name="cums", type="string", length=100, nullable=true)
     * 
     */
    private $cums;

    /**
     * @var string $nombre
     * 

     * @ORM\Column(name="nombre", type="string", length=150, nullable=false, unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(min=5,max=150)
     */
    private $nombre;

    /**
     * @var string $tipoMedicamento
     *
     * @ORM\Column(name="tipo_med", type="string", length=100, nullable=true)
     */
    private $tipoMedicamento;
    
    
    
    /**
     * @var string $tipoImv
     * 
     * @ORM\Column(name="tipo_imv", type="string", length=100, nullable=true)
     */
    private $tipoImv;
    
    /**
     * @var string $formaFarmaceutica
     * 
     * @ORM\Column(name="forma_farmaceutica", type="string",  length=40, nullable=true)     
     * 
     * @Assert\Length(min=3,max=10)
     */
    private $formaFarmaceutica;

    /**
     * @var string $concentracion
     * 
     * @ORM\Column(name="concentracion", type="string", length=30, nullable=true)     
     * 
     * @Assert\Length(min=3,max=12)
     */
    private $concentracion;

    /**
     * @var integer $uniMedida
     * 
     * @ORM\Column(name="uni_medida", type="string", length=100, nullable=true)     
     * 
     * @Assert\Length(min=1,max=20)
     * 
     */
    private $uniMedida;

    /**
     * @var string $jeringa
     *
     * @ORM\Column(name="jeringa", type="string", length=10, nullable=true)  
     *  
     * @Assert\Length(min=2,max=12)
     */
    private $jeringa;

    /**
     * @var string $dosis
     *
     * @ORM\Column(name="dosis", type="string", length=10, nullable=true)  
     *  
     * @Assert\Length(min=1,max=1)
     *
     */
    private $dosis;
    
    /**
     * @var CantT
     *
     * @ORM\Column(name="cant_total", type="integer", nullable=true)
     */
    private $cantT;
    
    /**
     * @var precioVenta
     *
     * @ORM\Column(name="precio_venta", type="integer", nullable=true)
     */
    private $precioVenta;
    
    
    /*
     * Get toString
    */
    public function __toString()
    {
    	return $this->getNombre();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Set codCups
     *
     * @param string $codCups
     * @return Imv
     */
    public function setCodCups($codCups)
    {
    	$this->codCups = $codCups;
    
    	return $this;
    }
    
    /**
     * Get codCups
     *
     * @return string
     */
    public function getCodCups()
    {
    	return $this->codCups;
    }
    
    
    
    /**
     * Set codAdmin
     *
     * @param string $codAdmin
     * @return Imv
     */
    public function setCodAdmin($codAdmin)
    {
    	$this->codAdmin = $codAdmin;
    
    	return $this;
    }
    
    /**
     * Get codAdmin
     *
     * @return string
     */
    public function getCodAdmin()
    {
    	return $this->codAdmin;
    }
    
    
    /**
     * Set cums
     *
     * @param string $cums
     * @return Imv
     */
    public function setCums($cums)
    {
    	$this->cums = $cums;
    
    	return $this;
    }
    
    /**
     * Get cums
     *
     * @return string
     */
    public function getCums()
    {
    	return $this->cums;
    }
    
        
    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Imv
     */
    public function setNombre($nombre)
    {
    	$this->nombre = $nombre;
    
    	return $this;
    }
    
    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
    	return $this->nombre;
    }
    
    
    /**
     * Set tipoImv
     *
     * @param string $tipoImv
     * @return Imv
     */
    public function setTipoImv($tipoImv)
    {
    	$this->tipoImv = $tipoImv;
    
    	return $this;
    }
    
    /**
     * Get tipoImv
     *
     * @return string
     */
    public function getTipoImv()
    {
    	return $this->tipoImv;
    }
    
    
    /**
     * Set tipoMedicamento
     *
     * @param string $tipoMedicamento
     * @return Imv
     */
    public function setTipoMedicamento($tipoMedicamento)
    {
    	$this->tipoMedicamento = $tipoMedicamento;
    
    	return $this;
    }
    
    /**
     * Get tipoMedicamento
     *
     * @return string
     */
    public function getTipoMedicamento()
    {
    	return $this->tipoMedicamento;
    }
    
      
        
    /**
     * Set formaFarmaceutica
     *
     * @param string $formaFarmaceutica
     * @return Imv
     */
    public function setFormaFarmaceutica($formaFarmaceutica)
    {
    	$this->formaFarmaceutica = $formaFarmaceutica;
    
    	return $this;
    }
    
    /**
     * Get formaFarmaceutica
     *
     * @return string
     */
    public function getFormaFarmaceutica()
    {
    	return $this->formaFarmaceutica;
    }
    
    
    
    /**
     * Set concentracion
     *
     * @param string $concentracion
     * @return Imv
     */
    public function setConcentracion($concentracion)
    {
    	$this->concentracion = $concentracion;
    
    	return $this;
    }
    
    /**
     * Get concentracion
     *
     * @return string
     */
    public function getConcentracion()
    {
    	return $this->concentracion;
    }
    
    
    /**
     * Set uniMedida
     *
     * @param string $uniMedida
     * @return Imv
     */
    public function setUniMedida($uniMedida)
    {
    	$this->uniMedida = $uniMedida;
    
    	return $this;
    }
    
    /**
     * Get uniMedida
     *
     * @return string
     */
    public function getUniMedida()
    {
    	return $this->uniMedida;
    }
    
    
    /**
     * Set jeringa
     *
     * @param string $jeringa
     * @return Imv
     */
    public function setJeringa($jeringa)
    {
    	$this->jeringa = $jeringa;
    
    	return $this;
    }
    
    /**
     * Get jeringa
     *
     * @return string
     */
    public function getJeringa()
    {
    	return $this->jeringa;
    }
    
    
    /**
     * Set dosis
     *
     * @param string $dosis
     * @return Imv
     */
    public function setDosis($dosis)
    {
    	$this->dosis = $dosis;
    
    	return $this;
    }
    
    /**
     * Get dosis
     *
     * @return string
     */
    public function getDosis()
    {
    	return $this->dosis;
    }
    
    /**
    * Set cantT
    *
    * @param integer $cantT
    * @return Inventario
    */
    public function setCantT($cantT)
    {
    	$this->cantT = $cantT;
    
    	return $this;
    }
    
    /**
     * Get cantT
     *
     * @return integer
     */
    public function getCantT()
    {
    	return $this->cantT;
    }
    
    
    /**
     * Set precioVenta
     *
     * @param integer $precioVenta
     * @return Inventario
     */
    public function setPrecioVenta($precioVenta)
    {
    	$this->precioVenta = $precioVenta;
    
    	return $this;
    }
    
    /**
     * Get precioVenta
     *
     * @return integer
     */
    public function getPrecioVenta()
    {
    	return $this->precioVenta;
    }
}