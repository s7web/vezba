<?php
namespace Helpers;

use Monolog\Logger;
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
     * @var Logger
     */
    private $logger;


    /**
     * SetUp class properties
     *
     * @param Request $request
     * @param EntityManager $entityManager
     * @param Logger $logger
     */
    public function __construct( Request $request, EntityManager $entityManager, Logger $logger )
    {
        $this->request       = $request;
        $this->entityManager = $entityManager;
        $this->logger        = $logger;
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

    /**
     * Get logger instance
     *
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }
}