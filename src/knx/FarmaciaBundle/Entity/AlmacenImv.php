<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\FarmaciaBundle\Entity\AlmacenImv
 *
 * @ORM\Table(name="almacen_imv")
 * @ORM\Entity
 */
class AlmacenImv
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     */
    private $imv;


     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Almacen")
     */
    private $almacen;



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
     * Set almacen
     *
     * @param \knx\ParametrizarBundle\Entity\Almacen $almacen
     * @return AlmacenImv
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
