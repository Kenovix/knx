<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * knx\FarmaciaBundle\Entity\CategoriaImv
 * @ORM\Table(name="categoria_imv")
 * @ORM\Entity
 */
class CategoriaImv
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
     * @var string nombre
     *
     * @ORM\Column(name="nombre", type="text", nullable=false)
     */
        private $nombre;
}
