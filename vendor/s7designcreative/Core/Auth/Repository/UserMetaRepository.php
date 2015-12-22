<?php
namespace S7D\Core\Auth\Repository;

use Doctrine\ORM\EntityRepository;
use S7D\Core\Auth\Entity\UserMeta;

class UserMetaRepository extends EntityRepository {

	public function get($user, $key, $default = null) {

		$um = $this->findOneBy(['option_key' => $key, 'user' => $user->getId()]);
		return $um ? json_decode($um->option_value, true) : $default;
	}

	public function set($user, $key, $value) {

		$um = $this->findOneBy(['option_key' => $key, 'user' => $user]);
		if(! $um) {
			$um = new UserMeta();
			$um->option_key = $key;
			$um->user = $user;
		}
		$um->option_value = json_encode($value);

		$this->getEntityManager()->persist($um);
		$this->getEntityManager()->flush();
	}
}
