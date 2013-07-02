<?php

namespace knx\ParametrizarBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\ParametrizarBundle\Entity\Contrato
 * 
 * @ORM\Table(name="contrato")
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Contrato
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
     * @var string $numero
     * 
     * @ORM\Column(name="numero", type="string", length=15, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=20, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $numero;
    
    /**
     * @var date $fechaInicio
     * 
     * @ORM\Column(name="fechaInicio", type="date", nullable=false)
     * @Assert\Date(message="Por favor ingresa fecha valida")
     */

    private $fechaInicio;
    
    /**
     * @var date $fechaFin
     * 
     * @ORM\Column(name="fechaFin", type="date", nullable=false)
     * @Assert\Date(message="Por favor ingresa fecha valida")
     */
    private $fechaFin;
    
          
    /**
     * @var string $contacto
     * 
     * @ORM\Column(name="contacto", type="string", length=80, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=80, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $contacto;
    
    /**
     * @var string $cargo
     *
     * @ORM\Column(name="cargo", type="string", length=30, nullable=false)
     * @Assert\MaxLength(limit=30, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $cargo;
    
    /**
     * @var string $telefono
     * 
     * @ORM\Column(name="telefono", type="string", nullable=true)
     * @Assert\Min(limit = "1000000", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
    private $telefono;

    /**
     * @var string $celular
     * 
     * @ORM\Column(name="celular", type="string", length=10, nullable=true)
     * @Assert\Min(limit = "3000000000", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
    private $celular;
    
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=200, nullable=true)
     * @Assert\Email(message = "El email '{{ value }}' no es valido.", checkMX = true)
     */
    private $email;
    
    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=false)
     * @Assert\Choice(choices = {"I", "A"}, message = "Selecciona una opción valida.")
     */
    private $estado;
    
    /**
     * @var integer $porcentaje
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="porcentaje", type="decimal", scale=2, nullable=false)
     * @Assert\Min(limit = "-50", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "100", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
    private $porcentaje;
    
    /**
     * @var string $tipo
     *
     * @ORM\Column(name="tipo", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=20, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $tipo;
    
    /**
     * @var string $observacion
     *
     * @ORM\Column(name="observacion", type="string", length=200, nullable=true)
     * @Assert\MaxLength(limit=200, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Contrato
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Contrato
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Contrato
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    
        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     * @return Contrato
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;
    
        return $this;
    }

    /**
     * Get contacto
     *
     * @return string 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     * @return Contrato
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    
        return $this;
    }

    /**
     * Get cargo
     *
     * @return string 
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Contrato
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     * @return Contrato
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    
        return $this;
    }

    /**
     * Get celular
     *
     * @return string 
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contrato
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Contrato
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
     * Set porcentaje
     *
     * @param float $porcentaje
     * @return Contrato
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;
    
        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float 
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     * @return Contrato
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
     * Set created
     *
     * @param \DateTime $created
     * @return Contrato
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
     * @return Contrato
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
     * Set cliente
     *
     * @param \knx\ParametrizarBundle\Entity\Cliente $cliente
     * @return Contrato
     */
    public function setCliente(\knx\ParametrizarBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
    
        return $this;
    }

    /**
     * Get cliente
     *
     * @return \knx\ParametrizarBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Contrato
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
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
}