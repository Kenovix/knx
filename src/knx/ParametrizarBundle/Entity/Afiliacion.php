<?php

namespace knx\ParametrizarBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\ParametrizarBundle\Entity\Afiliacion
 * 
 * @ORM\Table(name="afiliacion")
 * @ORM\Entity
 */
class Afiliacion
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Paciente")
     */
    private $paciente; 
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Cliente")
     */
    private $cliente;    
    
    /**
     * @var string $tipoRegist
     * 
     * @ORM\Column(name="nivel_rango", type="string", length=15, nullable=false)
     * @Assert\Length(max=15)
     */
    private $tipoRegist;
    
    /**
     * @var string $rango
     *
     * @ORM\Column(name="rango", type="string", length=1, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"A", "B", "C"}, message = "Selecciona una opción valida.")
     */
    private $rango;
          
    /**
     * @var string $observacion
     *
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
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
     * Set paciente
     *
     * @param knx\ParametrizarBundle\Entity\Paciente $paciente
     */
    public function setPaciente(\knx\ParametrizarBundle\Entity\Paciente $paciente)
    {
        $this->paciente = $paciente;
    }

    /**
     * Get paciente
     *
     * @return knx\ParametrizarBundle\Entity\Paciente 
     */
    public function getPaciente()
    {
        return $this->paciente;
    }

    /**
     * Set cliente
     *
     * @param knx\ParametrizarBundle\Entity\Cliente $cliente
     */
    public function setCliente(\knx\ParametrizarBundle\Entity\Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Get cliente
     *
     * @return knx\ParametrizarBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Afiliacion
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
     * @return Afiliacion
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
     * Set tipoRegist
     *
     * @param string $tipoRegist
     * @return Afiliacion
     */
    public function setTipoRegist($tipoRegist)
    {
        $this->tipoRegist = $tipoRegist;
    
        return $this;
    }

    /**
     * Get tipoRegist
     *
     * @return string 
     */
    public function getTipoRegist()
    {
        return $this->tipoRegist;
    }
    
    /**
     * Set rango
     *
     * @param string $rango
     * @return Paciente
     */
    public function setRango($rango)
    {
    	$this->rango = $rango;
    
    	return $this;
    }
    
    /**
     * Get rango
     *
     * @return string
     */
    public function getRango()
    {
    	return $this->rango;
    }
}