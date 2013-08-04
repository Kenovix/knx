<?php

namespace knx\ParametrizarBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;


/**
 * knx\ParametrizarBundle\Entity\Proveedor
 *
 * @ORM\Table(name="proveedor")
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("nit")
 */
 
class Proveedor
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
     * @var string $nit
     *
     * @ORM\Column(name="nit", type="string", length=13, unique=true, nullable=false)
     * @Assert\Length(max=12 , min=11)       
     */
    private $nit;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=false)
     * @Assert\Length(max=60 , min=3) 
     *
     */
    private $nombre;

     /**
     * @var integer $ciudad
     *
     * @ORM\Column(name="ciudad", type="string", length=160, nullable=false)
     * @Assert\Length(max=15 , min=3)
     */
    private $ciudad;


    /**
     * @var string $direccion
     *
     * @ORM\Column(name="direccion", type="string", length=60, nullable=true)
     */
    private $direccion;


    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=7, nullable=true)
	 *
     */
    private $telefono;


    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=200, nullable=true)
     */
    private $email;
    
    
    /**
     * @var almacen
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Almacen")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="almacen_id", referencedColumnName="id")
     * })
     */
        private $almacen;

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
     * Set nit
     *
     * @param string $nit
     * @return Proveedor
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
     * @return Proveedor
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
     * Set ciudad
     *
     * @param integer $ciudad
     * @return Proveedor
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    
        return $this;
    }

    /**
     * Get ciudad
     *
     * @return integer 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Proveedor
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
     * @return Proveedor
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
     * Set email
     *
     * @param string $email
     * @return Proveedor
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Proveedor
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
     * @return Proveedor
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
     * Set almacen
     *
     * @param \knx\ParametrizarBundle\Entity\Almacen $almacen
     * @return Proveedor
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