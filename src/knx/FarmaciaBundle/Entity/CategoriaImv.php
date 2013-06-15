<?php

namespace knx\FarmaciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 *
 * knx\FarmaciaBundle\Entity\CategoriaImv
 * 
 * @ORM\Table(name="categoria_imv")
 * @DoctrineAssert\UniqueEntity("nombre") 
 * @ORM\Entity
 * 
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
     * @ORM\Column(name="nombre", type="string", nullable=false,unique=true)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")     
     * 
     */
        private $nombre;
        
        
        
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
    	 * Set nombre
    	 *
    	 * @param string $nombre
     	* @return CategoriaImv
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
}



