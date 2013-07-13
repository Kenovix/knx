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
     * @var string $observacion
     * 
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     * @Assert\MaxLength(limit=200, message="El valor ingresado debe tener m�ximo {{ limit }} caracteres.")
     */
    private $observacion;

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
     * Set medicamento
     *
     * @param knx\HcBundle\Entity\Medicamento $medicamento
     */
    public function setMedicamento(\knx\HcBundle\Entity\Medicamento $medicamento)
    {
        $this->medicamento = $medicamento;
    }

    /**
     * Get medicamento
     *
     * @return knx\HcBundle\Entity\Medicamento 
     */
    public function getMedicamento()
    {
        return $this->medicamento;
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
}