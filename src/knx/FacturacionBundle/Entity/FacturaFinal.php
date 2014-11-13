<?php

namespace knx\FacturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use knx\ParametrizarBundle\Entity\Cliente;


/**
 * knx\FacturacionBundle\Entity\FacturaFinal
 *
 * @ORM\Table(name="factura_final")
 * @ORM\Entity
 */
class FacturaFinal {
    
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
     */
    private $fecha;
    
    /**
     * @var datetime $inicio
     *
     * @ORM\Column(name="inicio", type="datetime", nullable=true)
     */
    private $inicio;
    
    /**
     * @var datetime $fin
     *
     * @ORM\Column(name="fin", type="datetime", nullable=true)
     */
    private $fin;
    

    /**
     * @var datetime $fR
     * 
     * @ORM\Column(name="f_r", type="datetime", nullable=true)
     */
    private $fR;

     
    /**
     * @var string $concepto
     *
     * @ORM\Column(name="concepto", type="text", nullable=true)
     */
    private $concepto;
    
    /**
     * @var integer $iva
     *
     * @ORM\Column(name="iva", type="integer", nullable=true)
     */
    private $iva;
    
    /**
     * @var string $tipo
    
     * @ORM\Column(name="tipo", type="string", length=1, nullable=true)
     */
    private $tipo;

    /**
     * @var integer $valor
     * 
     * @ORM\Column(name="valor", type="integer", nullable=false)
     */
    private $valor;

    /**
     * @var integer $copago
     *
     * @ORM\Column(name="copago", type="integer", nullable=false)
     */
    private $copago;


    /**
     * @var integer $asumido
     *
     * @ORM\Column(name="asumido", type="integer", nullable=false)
     */
    private $asumido;
    
     /**
     * @var integer $cobrar
     *
     * @ORM\Column(name="cobrar", type="integer", nullable=false)
     */
    private $cobrar;
    
    
    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=true)
     */
    private $estado;
    

    /**
     * @var string $observacion
     *
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     */
    private $observacion;


    /**
     * @var Cliente
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Cliente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     * })
     */
    private $cliente;
 
   
     /**
	 * @var Motivo
	 *
	 * @ORM\Column(name="motivo", type="string", nullable=true)
	 */
	private $motivo;
        
        
        /**
	 * @var Nfactura
	 *
	 * @ORM\Column(name="nfcatura", type="string", nullable=true)
	 */
	private $nfactura;
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
     * @param datetime $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return datetime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
    
    /**
     * Set inicio
     *
     * @param datetime $inicio
     */
    public function setInicio($inicio)
    {
    	$this->inicio = $inicio;
    }
    
    /**
     * Get inicio
     *
     * @return datetime
     */
    public function getInicio()
    {
    	return $this->inicio;
    }
    
    /**
     * Set fin
     *
     * @param datetime $fin
     */
    public function setFin($fin)
    {
    	$this->fin = $fin;
    }
    
    /**
     * Get fin
     *
     * @return datetime
     */
    public function getFin()
    {
    	return $this->fin;
    }
    
   

    /**
     * Set fR
     *
     * @param datetime $fR
     */
    public function setFR($fR)
    {
        $this->fR = $fR;
    }

    /**
     * Get fR
     *
     * @return datetime 
     */
    public function getFR()
    {
        return $this->fR;
    }

    
    /**
     * Set concepto
     *
     * @param string $concepto
     */
    public function setConcepto($concepto)
    {
    	$this->concepto = $concepto;
    }
    
    /**
     * Get concepto
     *
     * @return string
     */
    public function getConcepto()
    {
    	return $this->concepto;
    }
    
    /**
     * Set iva
     *
     * @param integer $iva
     */
    public function setIva($iva)
    {
    	$this->iva = $iva;
    }
    
    /**
     * Get iva
     *
     * @return integer
     */
    public function getIva()
    {
    	return $this->iva;
    }
    
    /**
     * Set tipo
     *
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
    	$this->tipo = $tipo;
    }
    
    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
    	return $this->tipo;
    }

    /**
     * Set valor
     *
     * @param integer $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
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
     * Set copago
     *
     * @param integer $copago
     */
    public function setCopago($copago)
    {
        $this->copago = $copago;
    }

    /**
     * Get copago
     *
     * @return integer 
     */
    public function getCopago()
    {
        return $this->copago;
    }

    
    /**
     * Set asumido
     *
     * @param integer $asumido
     */
    public function setAsumido($asumido)
    {
        $this->asumido = $asumido;
    }

    /**
     * Get asumido
     *
     * @return integer 
     */
    public function getAsumido()
    {
        return $this->asumido;
    }
     
    /**
     * Set cobrar
     *
     * @param integer cobrar
     */
    public function setCobrar($cobrar)
    {
        $this->cobrar = $cobrar;
    }

    /**
     * Get cobrar
     *
     * @return integer 
     */
    public function getCobrar()
    {
        return $this->cobrar;
    }
    /**
     * Set estado
     *
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
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
     * Set cliente
     *
     * @param dlaser\ParametrizarBundle\Entity\Cliente $cliente
     */
    public function setCliente(\knx\ParametrizarBundle\Entity\Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Get cliente
     *
     * @return dlaser\ParametrizarBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
	 * Set motivo
	 *
	 * @param integer $motivo
	 * @return Factura
	 */
	public function setMotivo($motivo){
		
		$this->motivo = $motivo;
			
		return $this;
	}

	/**
	 * Get motivo
	 *
	 * @return integer 
	 */
	public function getMotivo() {
		return $this->motivo;
	}
        
         /**
	 * Set nfactura
	 *
	 * @param integer $nfactura
	 * @return Factura
	 */
	public function setNfactura($nfactura){
		
		$this->nfactura = $nfactura;
			
		return $this;
	}

	/**
	 * Get nfactura
	 *
	 * @return integer 
	 */
	public function getNfactura() {
		return $this->nfactura;
	}
}