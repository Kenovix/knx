<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\FarmaciaBundle\Entity\ImvFarmacia
 *
 * @ORM\Table(name="imv_farmacia")
 * @ORM\Entity
 */
class ImvFarmacia
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     */
    private $imv;


     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Farmacia")
     */
    private $farmacia;



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
     * Set farmacia
     *
     * @param \knx\FarmaciaBundle\Entity\Farmacia $farmacia
     * @return ImvFarmacia
     */
    public function setFarmacia(\knx\FarmaciaBundle\Entity\Farmacia $farmacia = null)
    {
        $this->farmacia = $farmacia;

        return $this;
    }

    /**
     * Get farmacia
     *
     * @return \knx\FarmaciaBundle\Entity\Farmacia
     */
    public function getFarmacia()
    {
        return $this->farmacia;
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
