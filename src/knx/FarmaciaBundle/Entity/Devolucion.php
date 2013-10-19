<?php

namespace knx\FarmaciaBundle\Entity;

use knx\ParametrizarBundle\Entity\Proveedor;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * knx\FarmaciaBundle\Entity\Devolucion
 *
 * @ORM\Table(name="devolucion")
 * @ORM\Entity
 */
class Devolucion
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
     * @var Imv
     *
     * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     * @ORM\JoinColumns({
     * 	@ORM\JoinColumn(name="imv_id", referencedColumnName="id")
     * })
     */

        private $imv;


     /**
       * @var Proveedor
       *
       * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Proveedor")
       * @ORM\JoinColumns({
       * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
       * })
       */

        private $proveedor;


        /**
       * @var Almacen
       *
       * @ORM\ManyToOne(targetEntity="knx\ParametrizarBundle\Entity\Almacen")
       * @ORM\JoinColumns({
       * @ORM\JoinColumn(name="almacen_id", referencedColumnName="id")
       * })
       */

        private $almacen;




    /**
     * @var datetime $fecha
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
        private $fecha;



     /**
     * @var cant
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     * @Assert\Range(min=1)
     */
        private $cant;



     /**
     * @var motivo
     *
     * @ORM\Column(name="motivo", type="string", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Length(min=3 , max=255)
     */
        private $motivo;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Devolucion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }



    /**
     * Set cant
     *
     * @param integer $cant
     * @return Devolucion
     */
    public function setCant($cant)
    {
        $this->cant = $cant;

        return $this;
    }

    /**
     * Get cant
     *
     * @return integer
     */
    public function getCant()
    {
        return $this->cant;
    }


    /**
     * Set motivo
     *
     * @param string $motivo
     * @return Devolucion
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Devolucion
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
     * @return Devolucion
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
     * Set imv
     *
     * @param \knx\FarmaciaBundle\Entity\Imv $imv
     * @return Devolucion
     */
    public function setImv(\knx\FarmaciaBundle\Entity\Imv $imv = null)
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
     * Set proveedor
     *
     * @param \knx\ParametrizarBundle\Entity\Proveedor $proveedor
     * @return Devolucion
     */
    public function setProveedor(\knx\ParametrizarBundle\Entity\Proveedor $proveedor = null)
    {
    	$this->proveedor = $proveedor;

    	return $this;
    }

    /**
     * Get proveedor
     *
     * @return \knx\ParametrizarBundle\Entity\Proveedor
     */
    public function getProveedor()
    {
    	return $this->proveedor;
    }

    /**
     * Set almacen
     *
     * @param \knx\ParametrizarBundle\Entity\Almacen $almacen
     * @return Devolucion
     */
    public function setAlmacen(\knx\ParametrizarBundle\Entity\Almacen $almacen = null)
    {
    	$this->almacen = $almacen;

    	return $this;
    }

    /**
     * Get almacen
     *
     * @return \knx\ParametrizarBundle\Entity\Almacen
     */
    public function getAlmacen()
    {
    	return $this->almacen;
    }


}