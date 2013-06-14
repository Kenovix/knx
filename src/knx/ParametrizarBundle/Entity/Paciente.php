<?php

namespace knx\ParametrizarBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * knx\ParametrizarBundle\Entity\Paciente
 *
 * @ORM\Table(name="paciente")
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("email")
 */
 
class Paciente
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
     * @var string $tipoId
     * 
     * @ORM\Column(name="tipo_id", type="string", length=2, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"CC", "RC", "TI", "CE","NU","AS"}, message = "Selecciona una opción valida.")
     *
     */
    private $tipoId;

    /**
     * @var string $identificacion
     * 
     * @ORM\Column(name="identificacion", type="string", length=13, unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10000", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
     */
    private $identificacion;

    /**
     * @var string $priNombre
     * 
     * @ORM\Column(name="pri_nombre", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=30, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")       *
     */
    private $priNombre;

    /**
     * @var string $segNombre
     * 
     * @ORM\Column(name="seg_nombre", type="string", length=30)
     * @Assert\MaxLength(limit=30, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     *
     */
    private $segNombre;

    /**
     * @var string $priApellido
     * 
     * @ORM\Column(name="pri_apellido", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=30, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     *
     */
    private $priApellido;

    /**
     * @var string $segApellido
     *
     * @ORM\Column(name="seg_apellido", type="string", length=30)
     * @Assert\MaxLength(limit=30, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $segApellido;

    /**
     * @var date $fN
     * 
     * @ORM\Column(name="f_n", type="date", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Date(message="Por favor ingresa fecha valida")
     */
    private $fN;

    /**
     * @var string $sexo
     * 
     * @ORM\Column(name="sexo", type="string", length=1, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"M", "F"}, message = "Selecciona una opción valida.")
     */
    private $sexo;


     /**
     * @var string $estaCivil
     * 
     * @ORM\Column(name="esta_civil", type="string", length=15, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"CASADO", "SOLTERO","UNION LIBRE"}, message = "Selecciona una opción valida.")
     */
       private $estaCivil;


    /**
     * @var integer $mupio
     * 
     * @ORM\Column(name="depto", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     *
     * 
     */
    private $depto;

    /**
     * @var integer $mupio
     * 
     * @ORM\Column(name="mupio", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     */
    private $mupio;

    /**
     * @var string $direccion
     *
     * @ORM\Column(name="direccion", type="string", length=60, nullable=true)
     * @Assert\MaxLength(limit=60, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $direccion;

    /**
     * @var string $zona
     *
     * @ORM\Column(name="zona", type="string", length=1, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"U", "R"}, message = "Selecciona una opción valida.")
     *
     */
    private $zona;

    /**
     * @var string $telefono      
     * 
     * @ORM\Column(name="telefono", type="string", length=7)
     * @Assert\Min(limit = "1000000", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
	 * @Assert\Max(limit = "9999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
	 *
     */
    private $telefono;

    /**
     * @var string $movil
     * 
     * @ORM\Column(name="movil", type="string", length=10)
     * @Assert\Min(limit = "3000000000", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
	 * @Assert\Max(limit = "9999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
     *
     */
    private $movil;

    /**
     * @var string $email
     * 
     * @ORM\Column(name="email", type="string", length=200)
     * @Assert\Email(message = "El email '{{ value }}' no es valido.", checkMX = true)
     */
    private $email;



    /**
     * @var string $rango
     * 
     * @ORM\Column(name="rango", type="string", length=1, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"A", "B", "C"}, message = "Selecciona una opción valida.")
     */
    private $rango;

    /**
     * @var string $tipoAfi
     * 
     * @ORM\Column(name="tipo_afi", type="string", length=1, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"B", "C"}, message = "Selecciona una opción valida.")
     *
     */
    private $tipoAfi;
    
     /**
     * @var string $tipoDes
     *
     * @ORM\Column(name="tipo_des", type="string", length=200, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"6", "7", "8"}, message = "Selecciona una opción valida.")
     */
     
     /**
     *
     * 6= "Des.Contributivo"
     * 7= "Des.Subsidiado"
     * 8= "Des.Vinculado"        *
     */
     
    private $tipoDes;
    
     /**
     * @var string $pertEtnica
     *
     * @ORM\Column(name="pert_etnica", type="string", length=1, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = { "1", "2", "3", "4", "5","6",}, message = "Selecciona una opción valida.")
     */
     
     
     /*  1 - Indógena
         2 - ROM (gitano)
         3 - Raizal (archipiólago de San Andrós y Providencia)
         4 - Palanquero de San  Basilio
         5 - Negro(a), Mulato(a),Afrocolombiano(a) o Afrodescendiente
         6 - Ninguno de los anteriores
     */
     
     
     

    private $pertEtnica;    
       
     /**
     * @var string $nivelEdu
     *
     * @ORM\Column(name="nivel_edu", type="string", length=15, nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"1", "2", "3", "4", " 5", "6", "7", "8", "9", "10", "11", "12","13"}, message = "Selecciona una opción valida.")
     */
     
     /*
     1- No Definido
     2- Preescolar
     3- Bósica Primaria
     4- Bósica Secundaria
     (Bachillerato Bósico)
     5- Media Acadómica o
     Clósica (Bachillerato
     Bósico)
     6- Media Tócnica
     (Bachillerato Tócnico)
     7- Normalista
     8- Tócnica Profesional
     9- Tecnológica
     10- Profesional
     11- Especialización
     12- Maestróa
     13- Doctorado
     */

     
    private $nivelEdu;
    
    
    /**
     * @ORM\OneToOne(targetEntity="knx\ParametrizarBundle\Entity\Ocupacion", mappedBy="paciente")
     */    

    private $ocupacion;
    
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
     * Set tipoId
     *
     * @param string $tipoId
     * @return Paciente
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;
    
        return $this;
    }

    /**
     * Get tipoId
     *
     * @return string 
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Set identificacion
     *
     * @param string $identificacion
     * @return Paciente
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
    
        return $this;
    }

    /**
     * Get identificacion
     *
     * @return string 
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * Set priNombre
     *
     * @param string $priNombre
     * @return Paciente
     */
    public function setPriNombre($priNombre)
    {
        $this->priNombre = $priNombre;
    
        return $this;
    }

    /**
     * Get priNombre
     *
     * @return string 
     */
    public function getPriNombre()
    {
        return $this->priNombre;
    }

    /**
     * Set segNombre
     *
     * @param string $segNombre
     * @return Paciente
     */
    public function setSegNombre($segNombre)
    {
        $this->segNombre = $segNombre;
    
        return $this;
    }

    /**
     * Get segNombre
     *
     * @return string 
     */
    public function getSegNombre()
    {
        return $this->segNombre;
    }

    /**
     * Set priApellido
     *
     * @param string $priApellido
     * @return Paciente
     */
    public function setPriApellido($priApellido)
    {
        $this->priApellido = $priApellido;
    
        return $this;
    }

    /**
     * Get priApellido
     *
     * @return string 
     */
    public function getPriApellido()
    {
        return $this->priApellido;
    }

    /**
     * Set segApellido
     *
     * @param string $segApellido
     * @return Paciente
     */
    public function setSegApellido($segApellido)
    {
        $this->segApellido = $segApellido;
    
        return $this;
    }

    /**
     * Get segApellido
     *
     * @return string 
     */
    public function getSegApellido()
    {
        return $this->segApellido;
    }

    /**
     * Set fN
     *
     * @param \DateTime $fN
     * @return Paciente
     */
    public function setFN($fN)
    {
        $this->fN = $fN;
    
        return $this;
    }

    /**
     * Get fN
     *
     * @return \DateTime 
     */
    public function getFN()
    {
        return $this->fN;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return Paciente
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    
        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set estaCivil
     *
     * @param string $estaCivil
     * @return Paciente
     */
    public function setEstaCivil($estaCivil)
    {
        $this->estaCivil = $estaCivil;
    
        return $this;
    }

    /**
     * Get estaCivil
     *
     * @return string 
     */
    public function getEstaCivil()
    {
        return $this->estaCivil;
    }

    /**
     * Set depto
     *
     * @param integer $depto
     * @return Paciente
     */
    public function setDepto($depto)
    {
        $this->depto = $depto;
    
        return $this;
    }

    /**
     * Get depto
     *
     * @return integer 
     */
    public function getDepto()
    {
        return $this->depto;
    }

    /**
     * Set mupio
     *
     * @param integer $mupio
     * @return Paciente
     */
    public function setMupio($mupio)
    {
        $this->mupio = $mupio;
    
        return $this;
    }

    /**
     * Get mupio
     *
     * @return integer 
     */
    public function getMupio()
    {
        return $this->mupio;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Paciente
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
     * Set zona
     *
     * @param string $zona
     * @return Paciente
     */
    public function setZona($zona)
    {
        $this->zona = $zona;
    
        return $this;
    }

    /**
     * Get zona
     *
     * @return string 
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Paciente
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
     * Set movil
     *
     * @param string $movil
     * @return Paciente
     */
    public function setMovil($movil)
    {
        $this->movil = $movil;
    
        return $this;
    }

    /**
     * Get movil
     *
     * @return string 
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Paciente
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

    /**
     * Set tipoAfi
     *
     * @param string $tipoAfi
     * @return Paciente
     */
    public function setTipoAfi($tipoAfi)
    {
        $this->tipoAfi = $tipoAfi;
    
        return $this;
    }

    /**
     * Get tipoAfi
     *
     * @return string 
     */
    public function getTipoAfi()
    {
        return $this->tipoAfi;
    }

    /**
     * Set pertEtnica
     *
     * @param string $pertEtnica
     * @return Paciente
     */
    public function setPertEtnica($pertEtnica)
    {
        $this->pertEtnica = $pertEtnica;
    
        return $this;
    }

    /**
     * Get pertEtnica
     *
     * @return string 
     */
    public function getPertEtnica()
    {
        return $this->pertEtnica;
    }

    /**
     * Set nivelEdu
     *
     * @param string $nivelEdu
     * @return Paciente
     */
    public function setNivelEdu($nivelEdu)
    {
        $this->nivelEdu = $nivelEdu;
    
        return $this;
    }

    /**
     * Get nivelEdu
     *
     * @return string 
     */
    public function getNivelEdu()
    {
        return $this->nivelEdu;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Paciente
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
     * @return Paciente
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
     * Set ocupacion
     *
     * @param \knx\ParametrizarBundle\Entity\Ocupacion $ocupacion
     * @return Paciente
     */
    public function setOcupacion(\knx\ParametrizarBundle\Entity\Ocupacion $ocupacion = null)
    {
        $this->ocupacion = $ocupacion;
    
        return $this;
    }

    /**
     * Get ocupacion
     *
     * @return \knx\ParametrizarBundle\Entity\Ocupacion 
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }
}