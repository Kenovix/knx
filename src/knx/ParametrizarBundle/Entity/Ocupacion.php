<?php

namespace knx\ParametrizarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\ParametrizarBundle\Entity\Ocupacion
 *
 * @ORM\Table(name="ocupacion")
 * @ORM\Entity
 */
 
class Ocupacion
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
      private $id;


    /**
     * @var string $codOcupacion
     *
     * @ORM\Column(name="cod_ocupacion", type="string", length=30, nullable=false)
     * @Assert\Length(max=30)
     */
      private $codOcupacion;


     /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=false)
     * @Assert\Length(max=30)
     */
      private $nombre;  

    /**
     * Set id
     *
     * @param integer $id
     * @return Ocupacion
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
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
     * Set codOcupacion
     *
     * @param string $codOcupacion
     * @return Ocupacion
     */
    public function setCodOcupacion($codOcupacion)
    {
        $this->codOcupacion = $codOcupacion;
    
        return $this;
    }

    /**
     * Get codOcupacion
     *
     * @return string 
     */
    public function getCodOcupacion()
    {
        return $this->codOcupacion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Ocupacion
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
    
    public function __toString()
    {
    	return $this->getNombre();
    }
}