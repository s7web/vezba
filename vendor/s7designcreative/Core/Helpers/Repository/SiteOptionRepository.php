<?php
namespace S7D\Core\Helpers\Repository;

use Doctrine\ORM\EntityRepository;
use S7D\Core\Helpers\Entity\SiteOption;

class SiteOptionRepository extends EntityRepository {

	public function get($key) {

		$value = $this->createQueryBuilder('o')->select('o.option_value')
			->where('o.option_key= :key')
			->setParameter(':key', $key)
			->getQuery()
			->getSingleScalarResult();

		$json = json_decode($value, true);
		return (json_last_error() == JSON_ERROR_NONE) ? $json : $value;
	}

	public function set($key, $value, $json = false) {

		$so = $this->findOneBy(['option_key' => $key]);
		if(! $so) {
			$so = new SiteOption();
			$so->option_key = $key;
		}
		$so->option_value = $json ? json_encode($value) : $value;

		$this->getEntityManager()->persist($so);
		$this->getEntityManager()->flush();
	}
}
