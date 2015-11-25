<?php
namespace S7D\Core\Helpers\Repository;

use Doctrine\ORM\EntityRepository;
use S7D\Core\Helpers\Entity\SiteOption;

class SiteOptionRepository extends EntityRepository {

	public function get($key, $default = null) {

		$so = $this->findOneBy(['option_key' => $key]);

		return $so ? json_decode($so->option_value, true) : $default;
	}

	public function set($key, $value) {

		$so = $this->findOneBy(['option_key' => $key]);
		if(! $so) {
			$so = new SiteOption();
			$so->option_key = $key;
		}
		$so->option_value = json_encode($value);

		$this->getEntityManager()->persist($so);
		$this->getEntityManager()->flush();
	}
}
