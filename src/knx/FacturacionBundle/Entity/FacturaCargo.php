<?php

namespace knx\FacturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\FacturacionBundle\Entity\FacturaCargo
 *
 * @ORM\Table(name="factura_cargo")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="knx\FacturacionBundle\Entity\Repository\FacturaCargoRepository")
 */
class FacturaCargo
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FacturacionBundle\Entity\Factura")
     */
     private $factura;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Cargo")
     */
	 private $cargo;

	 /**
	  * @var string $ambito
	  *
	  * @ORM\Column(name="ambito", type="integer", nullable=true)
	  */
	 private $ambito;

     /**
     * @var cantidad
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     */
     private $cantidad;

     /**
     * @var vrUnitario
     *
     * @ORM\Column(name="vr_unitario", type="integer", nullable=false)
     */
     private $vrUnitario;

     /**
     * @var vrFacturado
     *
     * @ORM\Column(name="vr_facturado", type="integer", nullable=false)
     */
     private $vrFacturado;

     /**
     * @var cobrarPte
     *
     * @ORM\Column(name="cobrar_pte", type="integer", nullable=false)
     */
     private $cobrarPte;


     /**
     * @var pagoPte
     *
     * @ORM\Column(name="pago_pte", type="integer", nullable=false)
     */
     private $pagoPte;


     /**
     * @var recoIps
     *
     * @ORM\Column(name="cargo_ips", type="integer", nullable=false)
     */
     private $recoIps;
        

     /**
     * @var valorTotal
     *
     * @ORM\Column(name="valor_total", type="integer", nullable=false)
     */
     private $valorTotal;


    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=true)
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
    * Set ambito
    *
    * @param string $ambito
    * @return Factura
    */
   public function setAmbito($ambito) {
   	$this->ambito = $ambito;
   
   	return $this;
   }
   
   /**
    * Get ambito
    *
    * @return string
    */
   public function getAmbito() {
   	return $this->ambito;
   }
    
    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * @return FacturaCargo
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
     * Set factura
     *
     * @param \knx\FacturacionBundle\Entity\Factura $factura
     * @return FacturaCargo
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

    /**
     * Set cargo
     *
     * @param \knx\ParametrizarBundle\Entity\Cargo $cargo
     * @return FacturaCargo
     */
    public function setCargo(\knx\ParametrizarBundle\Entity\Cargo $cargo)
    {
        $this->cargo = $cargo;
    
        return $this;
    }

    /**
     * Get cargo
     *
     * @return \knx\ParametrizarBundle\Entity\Cargo 
     */
    public function getCargo()
    {
        return $this->cargo;
    }
}