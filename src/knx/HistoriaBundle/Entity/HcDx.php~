<?php

namespace knx\HistoriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HcBundle\Entity\HcDx
 * 
 * @ORM\Table(name="hc_dx")
 * @ORM\Entity
 */
class HcDx
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\HistoriaBundle\Entity\Hc")
     */
    private $hc; 
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\HistoriaBundle\Entity\Cie")
     */
    private $cie;    
          
    /**
     * @var string $observacion
     * 
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)     
     */
    private $observacion;

    /**
     * Set observacion
     *
     * @param string $observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }

    /**
     * Get observacion
     *
     * @return string 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set hc
     *
     * @param knx\HcBundle\Entity\Hc $hc
     */
    public function setHc(\knx\HcBundle\Entity\Hc $hc)
    {
        $this->hc = $hc;
    }

    /**
     * Get hc
     *
     * @return knx\HcBundle\Entity\Hc 
     */
    public function getHc()
    {
        return $this->hc;
    }

    /**
     * Set cie
     *
     * @param knx\HcBundle\Entity\Cie $cie
     */
    public function setCie(\knx\HcBundle\Entity\Cie $cie)
    {
        $this->cie = $cie;
    }

    /**
     * Get cie
     *
     * @return knx\HcBundle\Entity\Cie
     */
    public function getCie()
    {
        return $this->cie;
    }
}