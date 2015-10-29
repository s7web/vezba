<?php

namespace S7D\Vendor\Blog\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository {

	public function number() {
		return $this->createQueryBuilder('p')->select('COUNT(p.id)')
			->getQuery()
			->getSingleScalarResult();
	}
}