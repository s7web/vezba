<?php
namespace S7D\Core\Helpers\Repository;

use Doctrine\ORM\EntityRepository;
use S7D\Core\Helpers\Entity\SiteOption;

class SiteOptionRepository extends EntityRepository {

	public function get($key) {

		$so = $this->createQueryBuilder('o')
			->where('o.option_key= :key')
			->setParameter(':key', $key)
			->getQuery()
			->getOneOrNullResult();
		if(!$so) {
			return null;
		}

		$json = json_decode($so->option_value, true);
		return (json_last_error() == JSON_ERROR_NONE) ? $json : $so->option_value;
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
