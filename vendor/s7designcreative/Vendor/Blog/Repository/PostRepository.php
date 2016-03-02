<?php

namespace S7D\Vendor\Blog\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository {

	public function number($filter) {
		$count = $this->createQueryBuilder('p')->select('COUNT(p.id)');
		if($filter) {
			//$count->where('p.author = ' . $filter['author']);
		}
		return $count->getQuery()->getSingleScalarResult();
	}

	public function getLatest($category, $limit, $exclude = 0) {

		return $this->createQueryBuilder('p')
			->join('p.categories', 'c')
			->where('c.name LIKE :category')
			->setParameter('category', '%' . $category . '%')
            ->andWhere('p.id <> :exclude')
            ->setParameter('exclude', $exclude)
			->orderBy('p.id', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

	public function search($q, $offset = 0, $limit = 10) {
		return $this->createQueryBuilder('p')
            ->where('p.title LIKE :q')
            ->orWhere('p.summary LIKE :q')
            ->orWhere('p.content LIKE :q')
            ->setParameter('q', '%' . $q . '%')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
	}

    public function getByTag($tag, $limit = 10) {
		return $this->createQueryBuilder('p')
			->join('p.tags', 't')
			->where('t.name = :tag')
			->setParameter('tag', $tag)
			->setMaxResults($limit)
			->getQuery()
			->getResult();
    }

	public function mostCommented($limit, $daysAgo) {
		return $this->createQueryBuilder('p')
			->select('p, count(c) as HIDDEN counter')
			->leftJoin('S7D\Vendor\Blog\Entity\Comment', 'c', 'with', 'c.post = p.id')
			->where('p.updated > :date')
            ->andWhere('p.type is NULL')
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
            ->andWhere('p.type is NULL')
			->setParameter('date', new \DateTime("-$daysAgo days"))
			->orderBy('p.updated', 'DESC')
			->orderBy('p.views', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

}
