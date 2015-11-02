<?php

namespace S7D\Core\Auth\Repository;

use Doctrine\ORM\EntityRepository;
use S7D\Core\Auth\Entity\Role;
use S7D\Core\Auth\Entity\User;

class UserRepository extends EntityRepository {

	public function insert($email, $password, $roles, $meta = [], $status = 0, $token = null, $id = 0) {

		if($id) {
			$user = $this->find($id);
		} else {
			$user = new User();
			$user->setToken($token);
			$user->setUserGroup(1);
			$user->setMeta($meta);
		}
		$user->setEmail($email);
		$user->setUsername($email);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$user->setPassword($password);
		$user->setRoles($roles);
		$user->setStatus($status);

		$this->getEntityManager()->persist($user);
		$this->getEntityManager()->flush();

		return $user->getId();
	}

}