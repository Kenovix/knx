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
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     */     
    private $imv;
        

     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Pyp")
     */  
    private $pyp;

     /**
     * @var integer $edadini
     *
     * @ORM\Column(name="edad_ini", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un numero valido")
     * @Assert\Max(limit = "99", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un numero valido")
     */
        private $edadini;

     /**
     * @var integer $edadfin
     *
     * @ORM\Column(name="edad_fin", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
     * @Assert\Max(limit = "99", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
     */
        private $edadfin;

     /**
     * @var integer $rango
     *
     * @ORM\Column(name="rango", type="integer", nullable=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
     * @Assert\Max(limit = "99", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un número válido")
     */
        private $rango;

     /**
     * @var string $sexo
     *
     * @ORM\Column(name="sexo", type="string", nullable=false)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Choice(choices = {"M", "F"}, message = "Selecciona una opciï¿½n valida.")
     */
        private $sexo;
}
