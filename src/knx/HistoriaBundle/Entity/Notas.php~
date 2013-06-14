<?php

namespace knx\HistoriaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HistoriaBundle\Entity\Notas
 *
 * @ORM\Table(name="notas")
 * @ORM\Entity
 */
class Notas
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
     * @var datetime $fecha
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     * @Assert\DateTime(message="Por favor ingresa fecha valida")
     */
    private $fecha;

    /**
     * @var text $resumenNota
     *
     * @ORM\Column(name="resumenNota", type="text", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     */
    private $resumenNota;

    /**
     * @var Hc
     *
     * @ORM\ManyToOne(targetEntity="knx\HistoriaBundle\Entity\Hc")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hc_id", referencedColumnName="id")
     * })
     */
    private $Hc;
    
    
    /**
     * @var Responsable
     *
     * @ORM\ManyToOne(targetEntity="knx\UsuarioBundle\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="responsable_id", referencedColumnName="id")
     * })
     */
    private $responsable;
    
     /** @var date $created
    *
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="created", type="date")
    */
   private $created;

     /**
      * @var datetime $updated
      *
      * @Gedmo\Timestampable(on="update")
      * @ORM\Column(name="updated", type="datetime")
      */
   private $updated;
     


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Notas
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set resumenNota
     *
     * @param string $resumenNota
     * @return Notas
     */
    public function setResumenNota($resumenNota)
    {
        $this->resumenNota = $resumenNota;
    
        return $this;
    }

    /**
     * Get resumenNota
     *
     * @return string 
     */
    public function getResumenNota()
    {
        return $this->resumenNota;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Notas
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Notas
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set Hc
     *
     * @param \knx\HcBundle\Entity\Hc $hc
     * @return Notas
     */
    public function setHc(\knx\HcBundle\Entity\Hc $hc = null)
    {
        $this->Hc = $hc;
    
        return $this;
    }

    /**
     * Get Hc
     *
     * @return \knx\HcBundle\Entity\Hc 
     */
    public function getHc()
    {
        return $this->Hc;
    }

    /**
     * Set responsable
     *
     * @param \knx\UsuarioBundle\Entity\Usuario $responsable
     * @return Notas
     */
    public function setResponsable(\knx\UsuarioBundle\Entity\Usuario $responsable = null)
    {
        $this->responsable = $responsable;
    
        return $this;
    }

    /**
     * Get responsable
     *
     * @return \knx\UsuarioBundle\Entity\Usuario 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }
}