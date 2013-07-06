<?php
namespace knx\HistoriaBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use knx\HistoriaBundle\Entity\Hc;

class NotasRepository extends EntityRepository {

	public function createEmptyHc($factura) {
		$em = $this->getEntityManager();

		$historia = new Hc();
		$historia->setFechaIngre(new \DateTime('now'));

		$serviIngre = $factura->getServicio();
		$historia->setFactura($factura);

		/* Se an validado algunos campos en la DB para q no sean nulos al igual q en el entity, por tal motivo 
		 * los campos que siguen a continuacion son campos obligatorios tanto para el code behind como para el user
		 * 
		 */
		$historia->setServiIngre($serviIngre->getId());
		$historia->setMotivo("Ingrese la informacion correspondiente.");
		$historia->setEnfermedad("Ingrese la informacion correspondiente");
		$historia->setConducta("Ingrese la informacion correspondiente");
		$historia->setTipoDx("Ingrese la informacion correspondiente");

		$em->persist($historia);
		$em->flush();

		return $historia;
	}
}
