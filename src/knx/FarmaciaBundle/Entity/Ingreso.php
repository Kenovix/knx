<?php

namespace knx\FarmaciaBundle\Entity;

use knx\ParametrizarBundle\Entity\Proveedor;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\FarmaciaBundle\Entity\Ingreso
 *
 * @ORM\Table(name="Ingreso")
 * @ORM\Entity
 */
class Ingreso
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
     * @var string $fecha
     * 
     * @ORM\Column(name="fecha", type="date",  nullable=false)
     */
    private $fecha;

    /**
     * @var string $numFact
     * 
     * @ORM\Column(name="num_fact", type="string", length=11, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(min=1)
     */
    private $numFact;
    
    /**
     * @var string $valorT
     * 
     * @ORM\Column(name="valor_t", type="integer",  nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Range(min=1,max=9999999999)
     */
    private $valorT;
    
    /**
     * @var string $valorTN
     * 
     * @ORM\Column(name="valor_n", type="integer",  nullable=false)
     *  @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     *  @Assert\Range(min=1,max=9999999999)
     */
    private $valorN;
    
    /**
     * @var string $valorIva
     * 
     * @ORM\Column(name="valor_iva", type="integer",  nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     */
    private $valorIva;

    
    /**
     * @var Almacen
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Almacen")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="almacen_id", referencedColumnName="id")
     * })
     */
    private $almacen;

    
    /**
     * @var Proveedor
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     * })
     */
    private $proveedor;

     /**
     * 
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="date")
     */
    private $created;

    /**
     * 
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    
    

    public function __construct()
    {
        
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Ingreso
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set numFact
     *
     * @param string $numFact
     * @return Ingreso
     */
    public function setNumFact($numFact)
    {
        $this->numFact = $numFact;
    
        return $this;
    }

    /**
     * Get numFact
     *
     * @return string 
     */
    public function getNumFact()
    {
        return $this->numFact;
    }

    /**
     * Set valorT
     *
     * @param integer $valorT
     * @return Ingreso
     */
    public function setValorT($valorT)
    {
        $this->valorT = $valorT;
    
        return $this;
    }

    /**
     * Get valorT
     *
     * @return integer 
     */
    public function getValorT()
    {
        return $this->valorT;
    }

    /**
     * Set valorN
     *
     * @param integer $valorN
     * @return Ingreso
     */
    public function setValorN($valorN)
    {
        $this->valorN = $valorN;
    
        return $this;
    }

    /**
     * Get valorN
     *
     * @return integer 
     */
    public function getValorN()
    {
        return $this->valorN;
    }

    /**
     * Set valorIva
     *
     * @param integer $valorIva
     * @return Ingreso
     */
    public function setValorIva($valorIva)
    {
        $this->valorIva = $valorIva;
    
        return $this;
    }

    /**
     * Get valorIva
     *
     * @return integer 
     */
    public function getValorIva()
    {
        return $this->valorIva;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Ingreso
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Ingreso
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
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
     * Set proveedor
     *
     * @param \knx\ParametrizarBundle\Entity\Proveedor $proveedor
     * @return Inventario
     */
    public function setProveedor(\knx\ParametrizarBundle\Entity\Proveedor $proveedor = null)
    {
    	$this->proveedor = $proveedor;
    
    	return $this;
    }
    
    /**
     * Get proveedor
     *
     * @return \knx\ParametrizarBundle\Entity\Proveedor
     */
    public function getProveedor()
    {
    	return $this->proveedor;
    }
    
    /**
     * Set almacen
     *
     * @param \knx\ParametrizarBundle\Entity\Almacen $almacen
     * @return Ingreso
     */
    public function setAlmacen(\knx\ParametrizarBundle\Entity\Almacen $almacen = null)
    {
        $this->almacen = $almacen;
    
        return $this;
    }

    /**
     * Get almacen
     *
     * @return \knx\ParametrizarBundle\Entity\Almacen 
     */
    public function getAlmacen()
    {
        return $this->almacen;
    }
}