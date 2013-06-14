<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\FarmaciaBundle\Entity\Imv
 *
 * @ORM\Table(name="imv")
 * @ORM\Entity
 * 
 */
class Imv
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
     * @var string $codFact
     * 
     * @ORM\Column(name="cod_fact", type="string", length=100, nullable=false)     
     */
    private $codFact;
    
    /**
     * @var string $codAdmin
     * 
     * @ORM\Column(name="cod_admin", type="string", length=100, nullable=false)
     */
    private $codAdmin;
    
    /**
     * @var string $cums
     * 
     * @ORM\Column(name="cums", type="string", length=100, nullable=false)
     */
    private $cums;

    /**
     * @var string $nombre
     * 
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=150, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $nombre;

    /**
     * @var string $tipoImv
     * 
     * @ORM\Column(name="tipo_imv", type="string", length=100, nullable=false)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=100, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $tipoImv;
    
    /**
     * @var string $formaFarmaceutica
     * 
     * @ORM\Column(name="forma_farmaceutica", type="string",  length=40, nullable=false)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=40, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $formaFarmaceutica;

    /**
     * @var string $concentracion
     * 
     * @ORM\Column(name="concentracion", type="string", length=30, nullable=false)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=30, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     */
    private $concentracion;

    /**
     * @var integer $uniMedida
     * 
     * @ORM\Column(name="uni_medida", type="string", length=100, nullable=true)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=100, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     * 
     */
    private $uniMedida;

    /**
     * @var string $jeringa
     *
     * @ORM\Column(name="jeringa", type="string", length=10, nullable=true)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=10, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     *
     */
    private $jeringa;

    /**
     * @var string $dosis
     *
     * @ORM\Column(name="dosis", type="string", length=10, nullable=true)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=10, message="El valor ingresado debe tener máximo {{ limit }} caracteres.")
     *
     */
    private $dosis;
      
}
