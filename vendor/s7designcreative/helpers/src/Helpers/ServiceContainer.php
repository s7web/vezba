<?php
namespace Helpers;

use Router\Request;
use Doctrine\ORM\EntityManager;

/**
 * Class ServiceContainer
 * @package Helpers
 */
class ServiceContainer
{
    /** @var Request */
    private $request;
    /** @var EntityManager */
    private $entityManager;

    /**
     * SetUp class properties
     *
     * @param Request       $request
     * @param EntityManager $entityManager
     */
    public function __construct( Request $request, EntityManager $entityManager )
    {
        $this->request       = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * Get request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get entity manager Doctrine
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}