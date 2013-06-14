<?php

namespace knx\HistoriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\HistoriaBundle\Entity\Medicamento
 *
 * @ORM\Table(name="medicamento")
 * @ORM\Entity
 * 
 * @ORM\Entity(repositoryClass="knx\HistoriaBundle\Entity\Repository\MedicamentoRepository")
 */
class Medicamento
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
     * @var string $principioActivo
     * 
     * @ORM\Column(name="principio_activo", type="string", length=200, nullable=false)
     * @Assert\NotBlank()
     * @Assert\MaxLength(limit=200, message="El principioActivo ingresado no puede sobrepasar {{ limit }} caracteres.")
     */
    private $principioActivo;

    /**
     * @var string $concentracion
     * 
     * @ORM\Column(name="concentracion", type="string", length=10, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=10, message="La concentracion ingresado no puede sobrepasar {{ limit }} caracteres.")
     */
    private $concentracion;

    /**
     * @var string $presentacion
     * 
     * @ORM\Column(name="presentacion", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=30, message="La presentacion ingresado no puede sobrepasar {{ limit }} caracteres.")
     */
    private $presentacion;

    /**
     * @var integer $posologia
     * 
     * @ORM\Column(name="posologia", type="string", length=100, nullable=true)
     * 
     */
     private $posologia;

    
    /**
     * @var boolean $estado
     * @ORM\Column(name="estado", type="string", length=1, nullable=true)
     */
    private $estado;

}
