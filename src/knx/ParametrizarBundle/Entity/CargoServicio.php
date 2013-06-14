<?php

namespace knx\ParametrizarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\ParametrizarBundle\Entity\CargoServicio
 *
 * @ORM\Table(name="cargo_servicio")
 * @ORM\Entity
 */
class CargoServicio
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Cargo")
     */
    private $cargo; 
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Servicio")
     */
    private $servicio;    
          
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
     * Set cargo
     *
     * @param knx\ParametrizarBundle\Entity\Cargo $cargo
     */
    public function setCargo(\knx\ParametrizarBundle\Entity\Cargo $cargo)
    {
        $this->cargo = $cargo;
    }

    /**
     * Get cargo
     *
     * @return knx\ParametrizarBundle\Entity\Cargo 
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set servicio
     *
     * @param knx\ParametrizarBundle\Entity\Servicio $servicio
     */
    public function setServicio(\knx\ParametrizarBundle\Entity\Servicio $servicio)
    {
        $this->servicio = $servicio;
    }

    /**
     * Get servicio
     *
     * @return knx\ParametrizarBundle\Entity\Servicio
     */
    public function getServicio()
    {
        return $this->servicio;
    }
}