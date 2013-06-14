<?php
namespace knx\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * knx\UsuarioBundle\Entity\Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Usuario
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
     * @var integer $cc
     * @ORM\Column(name="cc", type="integer", nullable=false, unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Max(limit = "9999999999999", message = "El valor cc no puede ser mayor de {{ limit }}",invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     *
     */
      private $cc;



     /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=60, message="El valor nombre debe tener como maximo {{ limit }} caracteres.")
     *
     */
      private $nombre;


    /**
     * @var string $apellido
     *
     * @ORM\Column(name="apellido", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=60, message="El valor apellido debe tener como maximo {{ limit }} caracteres.")
     *
     */
      private $apellido;


    /**
     * @var string $perfil
     *
     * @ORM\Column(name="perfil", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=13, message="El valor perfil debe tener como maximo {{ limit }} caracteres.")
     */
      private $perfil;
    
     /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=7, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Max(limit = "99999999999", message = "El valor telefono no puede ser mayor de {{ limit }}",invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
	 *
     */
      private $telefono;
    
    /**
     * @var string $direccion
     *
     * @ORM\Column(name="direccion", type="string", length=60, nullable=true)
     * @Assert\MaxLength(limit=60, message="El valor direccion debe tener como maximo {{ limit }} caracteres.")
     */
      private $direccion;
    
     /**
     * @var integer $tp
     *
     * @ORM\Column(name="tp", type="integer", nullable=false)
     * @Assert\MaxLength(limit=11, message="El valor tp debe tener como maximo {{ limit }} caracteres.")
     *
     *
     */
      private $tp;

     /**
     * @var string $especialidad
     *
     * @ORM\Column(name="especialidad", type="string", length=60, nullable=true)
     * @Assert\MaxLength(limit=30, message="El valor especialidad debe tener como maximo {{ limit }} caracteres.")
     */
      private $especialidad;


    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=true)
     * @Assert\Choice(choices = {"I", "A"}, message = "Selecciona una opci�n valida.")
     */
      private $estado;


     /**
     * @var string $contrasena
     *
     * @ORM\Column(name="contrasena", type="string", length=12, nullable=true, unique=true)
     * @Assert\MaxLength(limit=255, message="El valor password debe tener como maximo {{ limit }} caracteres.")
     */
       private $contrasena;
       
       
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=200, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=200, message="El valor password debe tener como maximo {{ limit }} caracteres.")
     */
       private $email;
       
       
    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="usuario_firma", fileNameProperty="firma")
     *
     * @var File $image
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, name="firma", nullable=true)
     *
     * @var string $firma
     */
    private $firma;
       
    /**
     * @var Empresa
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Empresa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     * })
     */
    private $empresa;
    
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
     * Set cc
     *
     * @param integer $cc
     * @return Usuario
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
    
        return $this;
    }

    /**
     * Get cc
     *
     * @return integer 
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
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
     * Set apellido
     *
     * @param string $apellido
     * @return Usuario
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    
        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set perfil
     *
     * @param string $perfil
     * @return Usuario
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
    
        return $this;
    }

    /**
     * Get perfil
     *
     * @return string 
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Usuario
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
     * Set direccion
     *
     * @param string $direccion
     * @return Usuario
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set tp
     *
     * @param integer $tp
     * @return Usuario
     */
    public function setTp($tp)
    {
        $this->tp = $tp;
    
        return $this;
    }

    /**
     * Get tp
     *
     * @return integer 
     */
    public function getTp()
    {
        return $this->tp;
    }

    /**
     * Set especialidad
     *
     * @param string $especialidad
     * @return Usuario
     */
    public function setEspecialidad($especialidad)
    {
        $this->especialidad = $especialidad;
    
        return $this;
    }

    /**
     * Get especialidad
     *
     * @return string 
     */
    public function getEspecialidad()
    {
        return $this->especialidad;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Usuario
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
     * Set contrasena
     *
     * @param string $contrasena
     * @return Usuario
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
    
        return $this;
    }

    /**
     * Get contrasena
     *
     * @return string 
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
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
     * Set firma
     *
     * @param string $firma
     * @return Usuario
     */
    public function setFirma($firma)
    {
        $this->firma = $firma;
    
        return $this;
    }

    /**
     * Get firma
     *
     * @return string 
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Usuario
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
     * @return Usuario
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
     * Set empresa
     *
     * @param \knx\ParametrizarBundle\Entity\Empresa $empresa
     * @return Usuario
     */
    public function setEmpresa(\knx\ParametrizarBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \knx\ParametrizarBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
}