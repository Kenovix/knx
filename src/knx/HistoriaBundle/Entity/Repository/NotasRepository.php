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
		
		$historia->setAntecedentesGenerales("NO REFIERE");
		$historia->setAntecedentesFami("NO REFIERE");
		$historia->setAntecedentesFisio("NO REFIERE");
		$historia->setAntecedentesGine("NO REFIERE");
		$historia->setAntecedentesPatologicos("NO REFIERE");
		$historia->setHabitosNocivos("NO REFIERE");
		$historia->setInmunizaciones("NO REFIERE");
		$historia->setAlergias("NO REFIERE");
		
		$historia->setOSentidos("NO REFIERE");
		$historia->setSEndocrino("NO REFIERE");
		$historia->setSNervioso("NO REFIERE");
		$historia->setSOsteoarticular("NO REFIERE");
		$historia->setARespiratorio("NO REFIERE");
		$historia->setACardiovascular("NO REFIERE");
		$historia->setADigestivo("NO REFIERE");
		$historia->setAGenitoUrinario("NO REFIERE");
		$historia->setAHematologico("NO REFIERE");
		
		$historia->setCabeza("NORMAL");
		$historia->setCara("NORMAL");
		$historia->setCuello("NORMAL");
		$historia->setOidos("NORMAL");
		$historia->setOjos("NORMAL");
		$historia->setNariz("NORMAL");
		$historia->setBoca("NORMAL");
		
		$historia->setTorax("NORMAL");
		$historia->setPulmones("NORMAL");
		$historia->setAbdomen("NORMAL");
		$historia->setEspalda("NORMAL");
		$historia->setEnfermedad("NORMAL");
		$historia->setGenitales("NORMAL");

		$em->persist($historia);
		$em->flush();

		return $historia;
	}
}
