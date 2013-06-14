<?php

namespace knx\ParametrizarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\ParametrizarBundle\Entity\Servicio
 *
 * @ORM\Table(name="servicio")
 * @ORM\Entity
 */
class Servicio
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
     * @ORM\Column(name="nombre", type="string", length=75, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=75, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $nombre;

    /**
     * @var Empresa
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Empresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     * })
     */
    private $empresa;

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
     * @return Servicio
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
     * @return Servicio
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