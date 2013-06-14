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
     * @var Proveedor
     *
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
     */
        private $cant;
        
        
    /**
     * @var CantE
     *
     * @ORM\Column(name="cant_entregada", type="integer", nullable=false)
     */
        private $cantE;


     /**
     * @var CantT
     *
     * @ORM\Column(name="cant_total", type="integer", nullable=false)
     */
        private $cantT;
        
        
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
     * @ORM\Column(name="precio_total", type="string", nullable=false)
     */
        private $precioTotal;

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
     * Set Cant
     *
     * @param integer $cant
     * @return Inventario
     */
    public function setCant($cant)
    {
        $this->Cant = $cant;
    
        return $this;
    }

    /**
     * Get Cant
     *
     * @return integer 
     */
    public function getCant()
    {
        return $this->Cant;
    }

    /**
     * Set CantE
     *
     * @param integer $cantE
     * @return Inventario
     */
    public function setCantE($cantE)
    {
        $this->CantE = $cantE;
    
        return $this;
    }

    /**
     * Get CantE
     *
     * @return integer 
     */
    public function getCantE()
    {
        return $this->CantE;
    }

    /**
     * Set CantT
     *
     * @param integer $cantT
     * @return Inventario
     */
    public function setCantT($cantT)
    {
        $this->CantT = $cantT;
    
        return $this;
    }

    /**
     * Get CantT
     *
     * @return integer 
     */
    public function getCantT()
    {
        return $this->CantT;
    }

    /**
     * Set PrecioCompra
     *
     * @param string $precioCompra
     * @return Inventario
     */
    public function setPrecioCompra($precioCompra)
    {
        $this->PrecioCompra = $precioCompra;
    
        return $this;
    }

    /**
     * Get PrecioCompra
     *
     * @return string 
     */
    public function getPrecioCompra()
    {
        return $this->PrecioCompra;
    }

    /**
     * Set PrecioVenta
     *
     * @param string $precioVenta
     * @return Inventario
     */
    public function setPrecioVenta($precioVenta)
    {
        $this->PrecioVenta = $precioVenta;
    
        return $this;
    }

    /**
     * Get PrecioVenta
     *
     * @return string 
     */
    public function getPrecioVenta()
    {
        return $this->PrecioVenta;
    }

    /**
     * Set PrecioTotal
     *
     * @param string $precioTotal
     * @return Inventario
     */
    public function setPrecioTotal($precioTotal)
    {
        $this->PrecioTotal = $precioTotal;
    
        return $this;
    }

    /**
     * Get PrecioTotal
     *
     * @return string 
     */
    public function getPrecioTotal()
    {
        return $this->PrecioTotal;
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
     * Set medicamento
     *
     * @param \knx\FarmaciaBundle\Entity\Medicamento $medicamento
     * @return Inventario
     */
    public function setMedicamento(\knx\FarmaciaBundle\Entity\Medicamento $medicamento = null)
    {
        $this->medicamento = $medicamento;
    
        return $this;
    }

    /**
     * Get medicamento
     *
     * @return \knx\FarmaciaBundle\Entity\Medicamento 
     */
    public function getMedicamento()
    {
        return $this->medicamento;
    }
}
