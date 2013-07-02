<?php
namespace knx\UsuarioBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\SecurityExtraBundle\Security\Util\String;

/**
 * knx\UsuarioBundle\Entity\Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Usuario extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
      protected $id;

      /**
     * @var integer $cc
     * @ORM\Column(name="cc", type="integer", nullable=false, unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Max(limit = "9999999999999", message = "El valor cc no puede ser mayor de {{ limit }}",invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Min(limit = "0", message = "La cedula no puede ser menor de {{ limit }}",invalidMessage = "El valor ingresado debe ser un número válido")
     */
      private $cc;

     /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=60, message="El valor nombre debe tener como maximo {{ limit }} caracteres.")
     */
      protected $nombre;

    /**
     * @var string $apellido
     *
     * @ORM\Column(name="apellido", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=60, message="El valor apellido debe tener como maximo {{ limit }} caracteres.")
     */
      private $apellido;
      
      /**
       * @var string $especialidad
       *
       * @ORM\Column(name="especialidad", type="string", length=60, nullable=true)
       * @Assert\MaxLength(limit=60, message="El valor especialidad debe tener como maximo {{ limit }} caracteres.")
       */
      private $especialidad;
      
      /**
       * @var string $rm
       *
       * @ORM\Column(name="rm", type="string", length=10, nullable=true)
       * @Assert\MaxLength(limit=10, message="El valor registro medico debe tener como maximo {{ limit }} caracteres.")
       */
      private $rm;
      
	
	public function __construct() {
		parent::__construct();

	}
	
	/**
	 * Agrega un rol al usuario.
	 * @throws Exception
	 * @param Rol $rol
	 */
	public function addRole( $rol )
	{
		if($rol == 1) {
			array_push($this->roles, 'ROLE_SUPER_ADMIN');
		}
		else if($rol == 2) {
			array_push($this->roles, 'ROLE_ADMIN');
		}
		else if($rol == 3) {
			array_push($this->roles, 'ROLE_FARMACIA');
		}
		else if($rol == 4) {
			array_push($this->roles, 'ROLE_MEDICO');
		}
		else if($rol == 5) {
			array_push($this->roles, 'ROLE_AUXILIAR');
		}
	}


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
     * Set rm
     *
     * @param string $rm
     * @return Usuario
     */
    public function setRm($rm)
    {
        $this->rm = $rm;
    
        return $this;
    }

    /**
     * Get rm
     *
     * @return string 
     */
    public function getRm()
    {
        return $this->rm;
    }
}