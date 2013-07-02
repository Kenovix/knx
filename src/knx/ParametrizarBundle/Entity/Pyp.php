<?php

namespace knx\ParametrizarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * knx\ParametrizarBundle\Entity\Pyp
 * @ORM\Table(name="categoria_pyp")
 * @ORM\Entity
 */
class Pyp
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
     * @var codigo
     *
     * @ORM\Column(name="codigo", type="integer", nullable=false)
     *
     */
        private $codigo;


    /**
     * @var string nombre
     *
     * @ORM\Column(name="nombre", type="text", nullable=false)
     */
        private $nombre;

	/**
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
     * Set codigo
     *
     * @param integer $codigo
     * @return Pyp
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Pyp
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
}