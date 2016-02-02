<?php
namespace S7D\Vendor\Blog\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository {

	public function getByRank($postId, $limit, $order = 'DESC') {
		return $this->createQueryBuilder('c')
			->select('c, MAX(c.likes - c.dislikes) as HIDDEN diff')
			->groupBy('c.id')
			->where('c.post = :postId')
			->setParameter('postId', $postId)
			->orderBy('diff', $order)
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}
}
