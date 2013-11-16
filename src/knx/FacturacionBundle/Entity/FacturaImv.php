<?php
namespace knx\FacturacionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\FacturacionBundle\Entity\FacturaImv
 *
 * @ORM\Table(name="factura_imv")
 * @ORM\Entity
 */
class FacturaImv
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     */
	private $imv;

	
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FacturacionBundle\Entity\Factura")
     */
     private $factura;


     /**
     * @var integer $cantidad
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)     
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
     private $cantidad;

     /**
     * @var integer $vrUnitario
     *
     * @ORM\Column(name="vr_unitario", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $vrUnitario;

     /**
     * @var integer $vrFacturado
     *
     * @ORM\Column(name="vr_facturado", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $vrFacturado;

     /**
     * @var integer $cobrarPte
     *
     * @ORM\Column(name="cobrar_pte", type="integer", nullable=false)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $cobrarPte;


     /**
     * @var integer $pagoPte
     *
     * @ORM\Column(name="pago_pte", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $pagoPte;


     /**
     * @var integer $recoIps
     *
     * @ORM\Column(name="reco_ips", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $recoIps;


     /**
     * @var integer $valorTotal
     *
     * @ORM\Column(name="valor_total", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $valorTotal;


    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=true)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=1, message="El valor ingresado debe tener m�ximo {{ limit }} caracteres.")
     */
    private $estado;
    
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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return FacturaImv
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set vrUnitario
     *
     * @param integer $vrUnitario
     * @return FacturaImv
     */
    public function setVrUnitario($vrUnitario)
    {
        $this->vrUnitario = $vrUnitario;
    
        return $this;
    }

    /**
     * Get vrUnitario
     *
     * @return integer 
     */
    public function getVrUnitario()
    {
        return $this->vrUnitario;
    }

    /**
     * Set vrFacturado
     *
     * @param integer $vrFacturado
     * @return FacturaImv
     */
    public function setVrFacturado($vrFacturado)
    {
        $this->vrFacturado = $vrFacturado;
    
        return $this;
    }

    /**
     * Get vrFacturado
     *
     * @return integer 
     */
    public function getVrFacturado()
    {
        return $this->vrFacturado;
    }

    /**
     * Set cobrarPte
     *
     * @param integer $cobrarPte
     * @return FacturaImv
     */
    public function setCobrarPte($cobrarPte)
    {
        $this->cobrarPte = $cobrarPte;
    
        return $this;
    }

    /**
     * Get cobrarPte
     *
     * @return integer 
     */
    public function getCobrarPte()
    {
        return $this->cobrarPte;
    }

    /**
     * Set pagoPte
     *
     * @param integer $pagoPte
     * @return FacturaImv
     */
    public function setPagoPte($pagoPte)
    {
        $this->pagoPte = $pagoPte;
    
        return $this;
    }

    /**
     * Get pagoPte
     *
     * @return integer 
     */
    public function getPagoPte()
    {
        return $this->pagoPte;
    }

    /**
     * Set recoIps
     *
     * @param integer $recoIps
     * @return FacturaImv
     */
    public function setRecoIps($recoIps)
    {
        $this->recoIps = $recoIps;
    
        return $this;
    }

    /**
     * Get recoIps
     *
     * @return integer 
     */
    public function getRecoIps()
    {
        return $this->recoIps;
    }

    /**
     * Set valorTotal
     *
     * @param integer $valorTotal
     * @return FacturaImv
     */
    public function setValorTotal($valorTotal)
    {
        $this->valorTotal = $valorTotal;
    
        return $this;
    }

    /**
     * Get valorTotal
     *
     * @return integer 
     */
    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return FacturaImv
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
     * Set created
     *
     * @param \DateTime $created
     * @return FacturaImv
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
     * @return FacturaImv
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
     * Set imv
     *
     * @param \knx\FarmaciaBundle\Entity\Imv $imv
     * @return FacturaImv
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
     * Set factura
     *
     * @param \knx\FacturacionBundle\Entity\Factura $factura
     * @return FacturaImv
     */
    public function setFactura(\knx\FacturacionBundle\Entity\Factura $factura)
    {
        $this->factura = $factura;
    
        return $this;
    }

    /**
     * Get factura
     *
     * @return \knx\FacturacionBundle\Entity\Factura 
     */
    public function getFactura()
    {
        return $this->factura;
    }
}