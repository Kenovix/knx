<?php

namespace knx\HistoriaBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class CieRepository extends EntityRepository {

	public function findByCieCode($chart) 
	{
		$em = $this->getEntityManager();			
		$dql = $em->createQuery(
								"SELECT
									cie.id, cie.codigo, cie.nombre
								 FROM
									HistoriaBundle:Cie cie
								 WHERE
									cie.codigo LIKE :codigo
								 ORDER BY
									cie.codigo ASC"
								);		
		$dql->setParameter('codigo', $chart.'%');		
		return $dql->getResult();
	}
	
	public function findByCieName($chart)
	{
		$em = $this->getEntityManager();
		$dql = $em->createQuery(
								"SELECT
									cie.id, cie.codigo, cie.nombre
								 FROM
									HistoriaBundle:Cie cie
								 WHERE
									cie.nombre LIKE :name
								 ORDER BY
									cie.nombre ASC"
		);
		$dql->setParameter('name', $chart.'%');
		return $dql->getResult();
	}
}
