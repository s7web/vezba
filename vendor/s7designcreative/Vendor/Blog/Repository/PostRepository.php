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

	public function getLatest($category, $limit) {

		return $this->createQueryBuilder('p')
			->join('p.categories', 'c')
			->where('c.name = :category')
			->setParameter('category', $category)
			->orderBy('p.id', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult();
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

    public function getByTag($tag, $limit = 10) {
        $query = $this->getEntityManager()->getConnection()->prepare(<<<SQL
SELECT p.id, p.title, p.summary
FROM post p
INNER JOIN post_has_tag as pt ON (p.id = pt.post_id)
INNER JOIN tag as t ON (t.id = pt.tag_id)
WHERE t.name LIKE :q
GROUP BY p.id
LIMIT {$limit}
SQL
        );
        $query->bindValue(':q', "%{$tag}%");
        $query->execute();
        return $query->fetchAll();
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
