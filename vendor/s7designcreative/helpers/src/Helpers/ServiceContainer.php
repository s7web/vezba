<?php
namespace Helpers;

use Router\Request;
use Doctrine\ORM\EntityManager;

class ServiceContainer {

    /** @var Request  */
    private $request;

    /** @var EntityManager */
    private $entityManager;

    public function __construct(Request $request, EntityManager $entityManager) {
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->entityManager;
    }
}
