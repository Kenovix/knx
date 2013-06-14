<?php

namespace knx\ParametrizarBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\ParametrizarBundle\Entity\ContratoCargo
 *
 * @ORM\Table(name="contrato_cargo")
 * @ORM\Entity
 */
class ContratoCargo
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Cargo")
     */
    private $cargo; 
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Contrato")
     */
    private $contrato;    
          
    /**
     * @var string $observacion
     * 
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     * @Assert\MaxLength(limit=200, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $observacion;
    
    
     /**
     * @var date $created
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
     * Set observacion
     *
     * @param string $observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
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

    /**
     * Set cargo
     *
     * @param knx\ParametrizarBundle\Entity\Cargo $cargo
     */
    public function setCargo(\knx\ParametrizarBundle\Entity\Cargo $cargo)
    {
        $this->cargo = $cargo;
    }

    /**
     * Get cargo
     *
     * @return knx\ParametrizarBundle\Entity\Cargo 
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set contrato
     *
     * @param knx\ParametrizarBundle\Entity\Contrato $contrato
     */
    public function setContrato(\knx\ParametrizarBundle\Entity\Contrato $contrato)
    {
        $this->contrato = $contrato;
    }

    /**
     * Get contrato
     *
     * @return knx\ParametrizarBundle\Entity\Contrato
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ContratoCargo
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
     * @return ContratoCargo
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
}