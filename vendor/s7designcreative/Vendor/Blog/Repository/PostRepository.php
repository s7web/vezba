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

	public function latestComments() {
		$query = $this->getEntityManager()->getConnection()->prepare(<<<SQL
SELECT p . *
FROM post_has_category pc
LEFT JOIN post p ON ( p.id = pc.post_id )
WHERE pc.category_id = 12
ORDER BY p.id DESC
LIMIT 3
SQL
		);
		$query->execute();
		return $query->fetchAll();

	}
}
