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

	public function getLastest($categoryId, $limit) {
		$query = $this->getEntityManager()->getConnection()->prepare(
'SELECT p . *
FROM post_has_category pc
LEFT JOIN post p ON ( p.id = pc.post_id )
WHERE pc.category_id = ' . $categoryId . '
ORDER BY p.id DESC
LIMIT ' . $limit
		);
		$query->execute();
		return $query->fetchAll();
	}

	public function search($q, $limit = 10) {
		return $this->createQueryBuilder('p')
            ->where('p.title LIKE :q')
            ->orWhere('p.summary LIKE :q')
            ->orWhere('p.content LIKE :q')
            ->setParameter('q', '%' . $q . '%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
	}

	public function mostCommented($limit, $daysAgo) {
		return $this->createQueryBuilder('p')
			->select('p, count(c) as HIDDEN counter')
			->leftJoin('S7D\Vendor\Blog\Entity\Comment', 'c', 'with', 'c.post = p.id')
			->where('p.updated > :date')
			->setParameter('date', new \DateTime("-$daysAgo days"))
			->groupBy('p.id')
			->orderBy('counter', 'DESC')
			->orderBy('p.updated', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

	public function getMostViewed($limit, $daysAgo) {
		return $this->createQueryBuilder('p')
			->where('p.updated > :date')
			->setParameter('date', new \DateTime("-$daysAgo days"))
			->orderBy('p.views', 'DESC')
			->orderBy('p.updated', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

}
