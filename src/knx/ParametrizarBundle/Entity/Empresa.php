<?php

namespace knx\ParametrizarBundle\Entity; 

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * knx\ParametrizarBundle\Entity\Empresa
 *
 * @ORM\Table(name="empresa")
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("nit")
 */
class Empresa
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
     * @var integer $habilitacion
     * 
     * @ORM\Column(name="habilitacion", type="string", length=15, nullable=false)
     */
    private $habilitacion;
    
    /**
     * @var string $nit
     * 
     * @ORM\Column(name="nit", type="string", length=12, nullable=false, unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(max=10000000000)  
     */
    private $nit;

    /**
     * @var string $nombre
     * 
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(min=3)  
     */
    private $nombre;
    
    /**
     * @var string $tipo
     * 
     * @ORM\Column(name="tipo", type="string", length=10, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(min=3)  
     */
    private $tipo; //"empresa social del estado o privada select"
    
    /**
     * @var string $direccion
     * 
     * @ORM\Column(name="direccion", type="string", length=50, nullable=false)
     * @Assert\Length(max=60)  
     */
    private $direccion;
    
    /**
     * @var string $telefono      
     * 
     * @ORM\Column(name="telefono", type="string", length=7, nullable=true)
      * @Assert\Min(limit = "1000000", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
	 * @Assert\Max(limit = "9999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     *
     */
    private $telefono;

    /**
     * @var integer $depto
     *
     * @ORM\Column(name="depto", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     */
    private $depto;
    
    /**
     * @var integer $mupio
     * 
     * @ORM\Column(name="mupio", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     */
    private $mupio;  
    
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
     * Set habilitacion
     *
     * @param integer $habilitacion
     * @return Empresa
     */
    public function setHabilitacion($habilitacion)
    {
        $this->habilitacion = $habilitacion;
    
        return $this;
    }

    /**
     * Get habilitacion
     *
     * @return integer 
     */
    public function getHabilitacion()
    {
        return $this->habilitacion;
    }

    /**
     * Set nit
     *
     * @param string $nit
     * @return Empresa
     */
    public function setNit($nit)
    {
        $this->nit = $nit;
    
        return $this;
    }

    /**
     * Get nit
     *
     * @return string 
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Empresa
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
     * Set tipo
     *
     * @param string $tipo
     * @return Empresa
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Empresa
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Empresa
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set depto
     *
     * @param integer $depto
     * @return Empresa
     */
    public function setDepto($depto)
    {
    	$this->depto = $depto;
    
    	return $this;
    }
    
    /**
     * Get depto
     *
     * @return integer
     */
    public function getDepto()
    {
    	return $this->depto;
    }
    
    /**
     * Set mupio
     *
     * @param integer $mupio
     * @return Empresa
     */
    public function setMupio($mupio)
    {
        $this->mupio = $mupio;
    
        return $this;
    }

    /**
     * Get mupio
     *
     * @return integer 
     */
    public function getMupio()
    {
        return $this->mupio;
    }

    
}