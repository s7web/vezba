<?php

namespace S7D\Vendor\Media\Repository;

use Doctrine\ORM\EntityRepository;

class MediaRepository extends EntityRepository {

	public function search($query, $type, $limit) {
		return $this->createQueryBuilder('m')
			->where('m.fileName LIKE :query')
			->andWhere('m.type = :type')
			->setMaxResults($limit)
			->setParameter('query', '%' . $query . '%')
			->setParameter('type', $type)
			->getQuery()->getResult();
	}

}