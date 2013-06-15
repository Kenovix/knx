<?php

namespace knx\FarmaciaBundle\Entity; 

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * knx\FarmaciaBundle\Entity\Farmacia
 *
 * @ORM\Table(name="farmacia")
 * @DoctrineAssert\UniqueEntity("nombre")
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
     * @var string $nombre
     * 
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false,unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MinLength(limit=3, message="El valor ingresado debe tener al menos {{ limit }} caracteres.")
     */
    private $nombre;
    
    /**
     * @var string $observacion
     * 
     * @ORM\Column(name="observacion", type="string", length=10)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MinLength(limit=3, message="El valor ingresado debe tener al menos {{ limit }} caracteres.")
     */
    private $observacion; 
    
        
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