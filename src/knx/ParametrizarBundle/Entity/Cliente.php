<?php

namespace knx\ParametrizarBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * knx\ParametrizarBundle\Entity\Cliente
 *
 * @ORM\Table(name="cliente")
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("nit")
 * @DoctrineAssert\UniqueEntity("nombre")
 */
class Cliente
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
     * @var string $codigo
     * 
     * @ORM\Column(name="codigo", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(max=20)  
     */
    private $codigo;

    /**
     * @var string $nit
     * 
     * @ORM\Column(name="nit", type="string", length=12, nullable=false, unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(max=12 , min=11)  
     */
    private $nit;

    /**
     * @var string $nombre
     * 
     * @ORM\Column(name="nombre", type="string", length=60, nullable=false, unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(max=60 , min=3)  
     */
    private $nombre;
    
    /**
     * @var string $razon
     *
     * @ORM\Column(name="razon", type="string", length=60, nullable=false)
     * @Assert\Length(max=255)
     * @Assert\Length(min=3) 
     */
    private $razon;
        
    /**
     * @var string $tipo
     *
     * @ORM\Column(name="regimen", type="string", length=2, nullable=true)
     * @Assert\Length(max=2)  
     */
    private $tipo;
    
    /**
     * @var string $direccion
     *
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     * @Assert\Length(max=80)  
     */
    private $direccion;
    
    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=10, nullable=true)
     * @Assert\Length(max=10)  
     */
    private $telefono;
    
    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=true)
     * @Assert\Length(max=1)  
     */
    private $estado;
    
    /**
     * @var Empresa
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Empresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     * })
     */
    private $empresa;
    
     /**
     * @var date $created
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
     * Set codigo
     *
     * @param string $codigo
     * @return Cliente
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nit
     *
     * @param string $nit
     * @return Cliente
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
     * @return Cliente
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
     * Set razon
     *
     * @param string $razon
     * @return Cliente
     */
    public function setRazon($razon)
    {
        $this->razon = $razon;
    
        return $this;
    }

    /**
     * Get razon
     *
     * @return string 
     */
    public function getRazon()
    {
        return $this->razon;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Cliente
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
     * @return Cliente
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
     * @return Cliente
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
     * Set estado
     *
     * @param string $estado
     * @return Cliente
     */
    public function setEstado($estado)
    {
    	$this->estado = $estado;
    
    	return $this;
    }
    
    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
    	return $this->estado;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set empresa
     *
     * @param \knx\ParametrizarBundle\Entity\Empresa $empresa
     * @return Cliente
     */
    public function setEmpresa(\knx\ParametrizarBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \knx\ParametrizarBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Cliente
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Cliente
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }
}