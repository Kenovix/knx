<?php

namespace knx\FarmaciaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * knx\FarmaciaBundle\Entity\Inventario
 * 
 * @ORM\Table(name="devolucion")
 * @ORM\Entity
 */
class Devolucion
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
     * @var Proveedor
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Proveedor")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     * })
     */

        private $proveedor;


      /**
     * @var Inventario
     *
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Inventario")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="inventario_id", referencedColumnName="id")
     * })
     */

        private $inventario;

    /**
     * @var datetime $fecha
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
        private $fecha;

     /**
     * @var codproducto
     *
     * @ORM\Column(name="cod_producto", type="integer", nullable=false)
     */
        private $codproducto;

     /**
     * @var nombreproducto
     *
     * @ORM\Column(name="nombre_producto", type="text", nullable=false)
     */
        private $nombreproducto;
        
     /**
     * @var cant
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     */
        private $cant;
        
        
     /**
     * @var  string dev
     *
     * @ORM\Column(name="devolucion_de", type="text", nullable=false)
     */
        private $dev;
        
        
     /**
     * @var motivo
     *
     * @ORM\Column(name="motivo", type="string", nullable=false)
     */
        private $motivo;

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
     * @return Devolucion
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
     * Set codproducto
     *
     * @param integer $codproducto
     * @return Devolucion
     */
    public function setCodproducto($codproducto)
    {
        $this->codproducto = $codproducto;
    
        return $this;
    }

    /**
     * Get codproducto
     *
     * @return integer 
     */
    public function getCodproducto()
    {
        return $this->codproducto;
    }

    /**
     * Set nombreproducto
     *
     * @param string $nombreproducto
     * @return Devolucion
     */
    public function setNombreproducto($nombreproducto)
    {
        $this->nombreproducto = $nombreproducto;
    
        return $this;
    }

    /**
     * Get nombreproducto
     *
     * @return string 
     */
    public function getNombreproducto()
    {
        return $this->nombreproducto;
    }

    /**
     * Set cant
     *
     * @param integer $cant
     * @return Devolucion
     */
    public function setCant($cant)
    {
        $this->cant = $cant;
    
        return $this;
    }

    /**
     * Get cant
     *
     * @return integer 
     */
    public function getCant()
    {
        return $this->cant;
    }

    /**
     * Set dev
     *
     * @param string $dev
     * @return Devolucion
     */
    public function setDev($dev)
    {
        $this->dev = $dev;
    
        return $this;
    }

    /**
     * Get dev
     *
     * @return string 
     */
    public function getDev()
    {
        return $this->dev;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return Devolucion
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    
        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Devolucion
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
     * @return Devolucion
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
     * @return Devolucion
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
     * Set inventario
     *
     * @param \knx\FarmaciaBundle\Entity\Inventario $inventario
     * @return Devolucion
     */
    public function setInventario(\knx\FarmaciaBundle\Entity\Inventario $inventario = null)
    {
        $this->inventario = $inventario;
    
        return $this;
    }

    /**
     * Get inventario
     *
     * @return \knx\FarmaciaBundle\Entity\Inventario 
     */
    public function getInventario()
    {
        return $this->inventario;
    }
}