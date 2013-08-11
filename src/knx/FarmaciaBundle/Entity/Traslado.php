<?php

namespace knx\FarmaciaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * knx\FarmaciaBundle\Entity\Traslado
 * 
 * @ORM\Table(name="traslado")
 * @ORM\Entity
 */
class Traslado
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
     * @var Imv
     *
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="imv_id", referencedColumnName="id")
     * })
     */
    private $imv;    
    

     /**
     * @var Farmacia
     *
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Farmacia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="farmacia_id", referencedColumnName="id")
     * })
     */
    private $farmacia;    
       

    /**
     * @var datetime $fecha
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
     private $fecha;

     /**
     * @var cant
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     * @Assert\Range(min=1)
     */
     private $cant;
     
     /**
      * @var string $tipo
      *
      * @ORM\Column(name="tipo_movi", type="string", nullable=false)
      * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
      * @Assert\Choice(choices = {"D", "T"}, message = "Selecciona una opci�n valida.")
      */
     private $tipo;

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
     * @return 
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
     * Set cant
     *
     * @param integer $cant
     * @return Traslado
     */
    public function setCant($cant)
    {
        $this->cant = $cant;
    
        return $this;
    }

    /**
     * Get cant
     *
     * @return integer 
     */
    public function getCant()
    {
        return $this->cant;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return Traslado
     */
    public function setTipo($tipo)
    {
    	$this->tipo = $tipo;
    
    	return $this;
    }
    
    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
    	return $this->tipo;
    }

  /**
     * Set imv
     *
     * @param \knx\FarmaciaBundle\Entity\Imv $imv
     * @return Traslado
     */
    public function setImv(\knx\FarmaciaBundle\Entity\Imv $imv = null)
    {
        $this->imv = $imv;
    
        return $this;
    }

    /**
     * Get imv
     *
     * @return \knx\FarmaciaBundle\Entity\Imv 
     */
    public function getImv()
    {
        return $this->imv;
    }
     /**
     * Set farmacia
     *
     * @param \knx\FarmaciaBundle\Entity\Farmacia $farmacia
     * @return Traslado
     */
    public function setFarmacia(\knx\FarmaciaBundle\Entity\Farmacia $farmacia = null)
    {
        $this->farmacia = $farmacia;
    
        return $this;
    }

    /**
     * Get farmacia
     *
     * @return \knx\FarmaciaBundle\Entity\Farmacia 
     */
    public function getFarmacia()
    {
        return $this->farmacia;
    }
}   