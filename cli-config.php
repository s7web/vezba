<?php
require_once 'app/init.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths     = array( SITE_PATH.'/src', SITE_PATH.'/vendor/s7designcreative/auth/src/' );
$isDevMode = false;

$dbParams = array(
    'host'     => DATABASE_HOST,
    'driver'   => DATABASE_DRIVER,
    'port'     => DATABASE_PORT,
    'user'     => DATABASE_USERNAME,
    'password' => DATABASE_PASSWORD,
    'dbname'   => DATABASE_NAME,
);

$config        = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
