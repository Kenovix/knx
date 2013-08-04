<?php

namespace knx\ParametrizarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 *
 * knx\ParametrizarBundle\Entity\Almacen
 * @ORM\Table(name="almacen")
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("nombre")
 */
class Almacen
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
     * @Assert\Length(min=3)  
     */
        private $nombre;
	
	/**
	 * @var empresa
	 *
	 * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Empresa")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="empresa_id", referencedColumnName="id" )
	 * })
	 */
	private $empresa;
	
	
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
     * @return Almacen
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
     * Set empresa
     *
     * @param \knx\ParametrizarBundle\Entity\Empresa $empresa
     * @return Almacen
     */
    public function setEmpresa(\knx\ParametrizarBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \knx\ParametrizarBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
}