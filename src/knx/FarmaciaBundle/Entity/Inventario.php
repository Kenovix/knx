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
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     */     
    private $imv;
        

     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Ingreso")
     */  
    private $ingreso;

        

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
     * @Assert\Range(
     *      min = "1",
     *      minMessage = "El menor número a ingresar es igual o mayor a 1" )
     */
        private $precioCompra;
        
           
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
