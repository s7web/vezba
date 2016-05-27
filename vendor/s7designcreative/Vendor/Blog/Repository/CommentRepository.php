<?php
namespace S7D\Vendor\Blog\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository {

	public function getByRank($postId, $limit, $order = 'DESC') {
		return $this->createQueryBuilder('c')
			->select('c, MAX(c.likes - c.dislikes) as HIDDEN diff')
			->groupBy('c.id')
			->where('c.post = :postId')
			->andWhere('c.status = 1')
			->setParameter('postId', $postId)
			->addOrderBy('diff', $order)
			->addOrderBy('c.id', 'DESC')
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}
}
