<?php

namespace knx\ParametrizarBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\ParametrizarBundle\Entity\Cargo
 *
 * @ORM\Table(name="cargo")
 * @ORM\Entity
 */
class Cargo
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
     * @var string $soat
     *
     * @ORM\Column(name="soat", type="string", length=8, nullable=false)
     *
     */
    private $soat;

    /**
     * @var string $cups
     * 
     * @ORM\Column(name="cups", type="string", length=8, nullable=false)    
     */
    private $cups;

    /**
     * @var string $nombre
     * 
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     *
     */
    private $nombre;

    /**
     * @var integer $valor
     * 
     * @ORM\Column(name="valor", type="integer", nullable=true)
     *
     */
    private $valor;
    
        
    /**
     * @var string $rips
     *
     * @ORM\Column(name="rips", type="string", length=2, nullable=false)
     */

    private $rips;
    
    
     /**
     * @var integer $tipoCons
     *
     * @ORM\Column(name="tipo_cons", type="integer", length=2, nullable=false)
     */

    private $tipoCons;
    
     /**
     * @var integer $tipoProc
     *
     * @ORM\Column(name="tipoProc", type="integer", length=1, nullable=false)
     */

    private $tipoProc;
    
    /**
     * @var integer $tipoSer
     *
     * @ORM\Column(name="tipoSer", type="integer", length=1, nullable=false)
     */

    private $tipoSer;
    
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
     * Set soat
     *
     * @param string $soat
     * @return Cargo
     */
    public function setSoat($soat)
    {
        $this->soat = $soat;
    
        return $this;
    }

    /**
     * Get soat
     *
     * @return string 
     */
    public function getSoat()
    {
        return $this->soat;
    }

    /**
     * Set cups
     *
     * @param string $cups
     * @return Cargo
     */
    public function setCups($cups)
    {
        $this->cups = $cups;
    
        return $this;
    }

    /**
     * Get cups
     *
     * @return string 
     */
    public function getCups()
    {
        return $this->cups;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Cargo
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
     * Set valor
     *
     * @param integer $valor
     * @return Cargo
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    
        return $this;
    }

    /**
     * Get valor
     *
     * @return integer 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set rips
     *
     * @param string $rips
     * @return Cargo
     */
    public function setRips($rips)
    {
        $this->rips = $rips;
    
        return $this;
    }

    /**
     * Get rips
     *
     * @return string 
     */
    public function getRips()
    {
        return $this->rips;
    }

    /**
     * Set tipoCons
     *
     * @param integer $tipoCons
     * @return Cargo
     */
    public function setTipoCons($tipoCons)
    {
        $this->tipoCons = $tipoCons;
    
        return $this;
    }

    /**
     * Get tipoCons
     *
     * @return integer 
     */
    public function getTipoCons()
    {
        return $this->tipoCons;
    }

    /**
     * Set tipoProc
     *
     * @param integer $tipoProc
     * @return Cargo
     */
    public function setTipoProc($tipoProc)
    {
        $this->tipoProc = $tipoProc;
    
        return $this;
    }

    /**
     * Get tipoProc
     *
     * @return integer 
     */
    public function getTipoProc()
    {
        return $this->tipoProc;
    }

    /**
     * Set tipoSer
     *
     * @param integer $tipoSer
     * @return Cargo
     */
    public function setTipoSer($tipoSer)
    {
        $this->tipoSer = $tipoSer;
    
        return $this;
    }

    /**
     * Get tipoSer
     *
     * @return integer 
     */
    public function getTipoSer()
    {
        return $this->tipoSer;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Cargo
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
     * @return Cargo
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