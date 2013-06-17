<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * knx\FarmaciaBundle\Entity\ImvPyp
 * @ORM\Table(name="imv_pyp")
 * @ORM\Entity
 */
class ImvPyp
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
     * @var imv
     *
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="imv_id", referencedColumnName="id")
     * })
     */   
    private $imv;
        

      /**
     * @var pyp
     *
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Pyp")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="pyp_id", referencedColumnName="id")
     * })
     */  
    private $pyp;

     /**
     * @var integer $edadIni
     *
     * @ORM\Column(name="edad_ini", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un numero valido")
     * @Assert\Max(limit = "99", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un numero valido")
     */
        private $edadIni;

     /**
     * @var integer $edadFin
     *
     * @ORM\Column(name="edad_fin", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "99", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $edadFin;

     /**
     * @var integer $rango
     *
     * @ORM\Column(name="rango", type="integer", nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "99", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $rango;

     /**
     * @var string $sexo
     *
     * @ORM\Column(name="sexo", type="string", nullable=false)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"M", "F","A"}, message = "Selecciona una opci�n valida.")
     */
        private $sexo;
        
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
         * Set edadIni
         *
         * @param integer $edadIni
         * @return CargoPyp
         */
        public function setEdadIni($edadIni)
        {
        	$this->edadIni = $edadIni;
        
        	return $this;
        }
        
        /**
         * Get edadIni
         *
         * @return integer
         */
        public function getEdadIni()
        {
        	return $this->edadIni;
        }
        
        /**
         * Set edadFin
         *
         * @param integer $edadFin
         * @return CargoPyp
         */
        public function setEdadFin($edadFin)
        {
        	$this->edadFin = $edadFin;
        
        	return $this;
        }
        
        /**
         * Get edadFin
         *
         * @return integer
         */
        public function getEdadFin()
        {
        	return $this->edadFin;
        }
        
        /**
         * Set rango
         *
         * @param integer $rango
         * @return CargoPyp
         */
        public function setRango($rango)
        {
        	$this->rango = $rango;
        
        	return $this;
        }
        
        /**
         * Get rango
         *
         * @return integer
         */
        public function getRango()
        {
        	return $this->rango;
        }
        
        /**
         * Set sexo
         *
         * @param string $sexo
         * @return CargoPyp
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
         * Set imv
         *
         * @param \knx\FarmaciaBundle\Entity\Imv $imv
         * @return ImvPyp
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
         * Set pyp
         *
         * @param \knx\ParametrizarBundle\Entity\Pyp $pyp
         * @return CargoPyp
         */
        public function setPyp(\knx\ParametrizarBundle\Entity\Pyp $pyp)
        {
        	$this->pyp = $pyp;
        
        	return $this;
        }
        
        /**
         * Get pyp
         *
         * @return \knx\ParametrizarBundle\Entity\Pyp
         */
        public function getPyp()
        {
        	return $this->pyp;
        }
        }

