<?php

namespace S7D\Vendor\Media\Repository;

use Doctrine\ORM\EntityRepository;

class MediaRepository extends EntityRepository {

	public function search($query, $types, $limit) {
		return $this->createQueryBuilder('m')
			->where('m.fileName LIKE :query')
			->andWhere('m.type IN (:types)')
			->andWhere('m.parent IS NULL')
			->orderBy('m.id', 'DESC')
			->setMaxResults($limit)
			->setParameter('query', '%' . $query . '%')
			->setParameter('types', $types)
			->getQuery()->getResult();
	}

}