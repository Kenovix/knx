<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * knx\FarmaciaBundle\Entity\Inventario
 * 
 * @ORM\Table(name="farmacia")
 * @ORM\Entity
 */
class Farmacia
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
     * @var string nombre
     *
     * @ORM\Column(name="nombre", type="text", nullable=false)
     */
        private $nombre;
        
        
        
     /**
     * @var string observacion
     *
     * @ORM\Column(name="observacion", type="text", nullable=false)
     */
        private $observacion;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Farmacia
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
     * Set observacion
     *
     * @param string $observacion
     * @return Farmacia
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    
        return $this;
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
}