<?php

namespace S7D\Vendor\Blog\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository {

	public function number($filter) {
		$count = $this->createQueryBuilder('p')->select('COUNT(p.id)');
		if($filter) {
			$count->where('p.author = ' . $filter['author']);
		}
		return $count->getQuery()->getSingleScalarResult();
	}
}
