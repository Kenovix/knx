<?php

namespace knx\ParametrizarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaPyp
 */
class CategoriaPyp
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $codcategoria;

    /**
     * @var string
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
     * @return CategoriaPyp
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
     * @return CategoriaPyp
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
