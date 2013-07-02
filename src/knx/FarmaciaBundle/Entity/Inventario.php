<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\FarmaciaBundle\Entity\Inventario
 * 
 * @ORM\Table(name="inventario")
 * @ORM\Entity
 */
class Inventario
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
     * @var Ingreso
     *
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Ingreso")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="ingreso_id", referencedColumnName="id")
     * })
     */

        private $ingreso;

     /**
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Proveedor")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     * })
     */

        private $proveedor;


      /**
     * @var Imv
     *
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="imv_id", referencedColumnName="id")
     * })
     */

        private $imv;
        

     /**
     * @var Cant
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     * @Assert\Range(
     *      min = "1",
     *      max = "10000",
     *      minMessage = "El menor número a ingresar es 1",
     *      maxMessage = "El mayor número a ingresar es 10000"
     * )
     */
        private $cant;
        

        
     /**
     * @var PrecioCompra
     *
     * @ORM\Column(name="precio_compra", type="string", nullable=false)
     */
        private $precioCompra;
        
        
     /**
     * @var PrecioVenta
     *
     * @ORM\Column(name="precio_venta", type="string", nullable=false)
     */
        private $precioVenta;
        
     /**
     * @var PrecioTotal
     *
     * @ORM\Column(name="precio_total", type="string", nullable=true)
     */
        private $precioTotal;

        /*
         * Get toString
        */
        public function __toString()
        {
        	return $this->getPrecioCompra();
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
     * Set cant
     *
     * @param integer $cant
     * @return Inventario
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
     * Set precioCompra
     *
     * @param string $precioCompra
     * @return Inventario
     */
    public function setPrecioCompra($precioCompra)
    {
        $this->precioCompra = $precioCompra;
    
        return $this;
    }

    /**
     * Get PrecioCompra
     *
     * @return string 
     */
    public function getPrecioCompra()
    {
        return $this->precioCompra;
    }

    /**
     * Set precioVenta
     *
     * @param string $precioVenta
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
     * @return string 
     */
    public function getPrecioVenta()
    {
        return $this->precioVenta;
    }

    /**
     * Set precioTotal
     *
     * @param string $precioTotal
     * @return Inventario
     */
    public function setPrecioTotal($precioTotal)
    {
        $this->precioTotal = $precioTotal;
    
        return $this;
    }

    /**
     * Get precioTotal
     *
     * @return string 
     */
    public function getPrecioTotal()
    {
        return $this->precioTotal;
    }

    /**
     * Set ingreso
     *
     * @param \knx\FarmaciaBundle\Entity\Ingreso $ingreso
     * @return Inventario
     */
    public function setIngreso(\knx\FarmaciaBundle\Entity\Ingreso $ingreso = null)
    {
        $this->ingreso = $ingreso;
    
        return $this;
    }

    /**
     * Get ingreso
     *
     * @return \knx\FarmaciaBundle\Entity\Ingreso 
     */
    public function getIngreso()
    {
        return $this->ingreso;
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
     * Set imv
     *
     * @param \knx\FarmaciaBundle\Entity\Imv $imv
     * @return Inventario
     */
    public function setImv(\knx\FarmaciaBundle\Entity\Imv $imv = null)
    {
        $this->imv = $imv;
    
        return $this;
    }

    /**
     * Get imv
     *
     * @return \knx\FarmaciaBundle\Entity\Imv 
     */
    public function getImv()
    {
        return $this->imv;
    }
}
