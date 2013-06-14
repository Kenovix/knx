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
     * @var codcategoria
     *
     * @ORM\Column(name="cod_categoria", type="integer", nullable=false)
     *
     */
        private $codcategoria;


     /**
     * @var string nombre
     *
     * @ORM\Column(name="nombre", type="text", nullable=false)
     */
        private $nombre;

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
     * Set codcategoria
     *
     * @param integer $codcategoria
     * @return Pyp
     */
    public function setCodcategoria($codcategoria)
    {
        $this->codcategoria = $codcategoria;
    
        return $this;
    }

    /**
     * Get codcategoria
     *
     * @return integer 
     */
    public function getCodcategoria()
    {
        return $this->codcategoria;
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