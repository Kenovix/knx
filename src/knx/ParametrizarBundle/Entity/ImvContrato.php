<?php
namespace knx\ParametrizarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\ParametrizarBundle\Entity\ImvContrato
 *
 * @ORM\Table(name="imv_contrato")
 * @ORM\Entity
 */
class ImvContrato
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     */
    private $imv;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Contrato")
     */
    private $contrato;
    
    /**
     * @var integer $precio
     *
     * @ORM\Column(name="precio", type="integer", nullable=true)
     */
    private $precio;
    
    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=false)
     */
    private $estado;
          
    /**
     * @var string $observacion
     * 
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     * @Assert\MaxLength(limit=200, message="El valor ingresado debe tener m�ximo {{ limit }} caracteres.")
     */
    private $observacion;

    /**
     * Set precio
     *
     * @param integer $precio
     * @return ImvContrato
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    
        return $this;
    }

    /**
     * Get precio
     *
     * @return integer 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return ImvContrato
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return ImvContrato
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    
        return $this;
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
     * Set imv
     *
     * @param \knx\FarmaciaBundle\Entity\Imv $imv
     * @return ImvContrato
     */
    public function setImv(\knx\FarmaciaBundle\Entity\Imv $imv)
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
     * Set contrato
     *
     * @param \knx\ParametrizarBundle\Entity\Contrato $contrato
     * @return ImvContrato
     */
    public function setContrato(\knx\ParametrizarBundle\Entity\Contrato $contrato)
    {
        $this->contrato = $contrato;
    
        return $this;
    }

    /**
     * Get contrato
     *
     * @return \knx\ParametrizarBundle\Entity\Contrato 
     */
    public function getContrato()
    {
        return $this->contrato;
    }
}