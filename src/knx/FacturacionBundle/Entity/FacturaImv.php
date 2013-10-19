<?php
namespace knx\FacturacionBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * knx\FacturacionBundle\Entity\FacturaImv
 *
 * @ORM\Table(name="factura_imv")
 * @ORM\Entity
 */
class FacturaImv
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="knx\FarmaciaBundle\Entity\Imv")
     */
	private $imv;

	
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="knx\FacturacionBundle\Entity\Factura")
     */
     private $factura;


     /**
     * @var integer $cantidad
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)     
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
     private $cantidad;

     /**
     * @var integer $vrUnitario
     *
     * @ORM\Column(name="vr_unitario", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "1", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $vrUnitario;

     /**
     * @var integer $vrFacturado
     *
     * @ORM\Column(name="vr_facturado", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $vrFacturado;

     /**
     * @var integer $cobrarPte
     *
     * @ORM\Column(name="cobrar_pte", type="integer", nullable=false)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $cobrarPte;


     /**
     * @var integer $pagoPte
     *
     * @ORM\Column(name="pago_pte", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $pagoPte;


     /**
     * @var integer $recoIps
     *
     * @ORM\Column(name="reco_ips", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $recoIps;


     /**
     * @var integer $valorTotal
     *
     * @ORM\Column(name="valor_total", type="integer", nullable=false)
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\Min(limit = "10", message = "El valor ingresado no puede ser menor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     * @Assert\Max(limit = "9999999999999", message = "El valor ingresado no puede ser mayor de {{ limit }}", invalidMessage = "El valor ingresado debe ser un n�mero v�lido")
     */
        private $valorTotal;


    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=true)     
     * @Assert\NotBlank(message="El valor ingresado no puede estar vacio.")
     * @Assert\MaxLength(limit=1, message="El valor ingresado debe tener m�ximo {{ limit }} caracteres.")
     */
    private $estado;
    
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
}
