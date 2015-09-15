<?php

require_once '../app/config/site_config.php';
require_once SITE_PATH .'/vendor/autoload.php';

use Symfony\Component\Yaml\Parser;

$yaml    = new Parser();
$router  = $yaml->parse( file_get_contents( APP_PATH.'/config/routes.yml' ) );
$aclList = $yaml->parse( file_get_contents( APP_PATH . '/config/acl.yml' ) );
/** @var \Router\Router $routes */
$routes = new \Router\Router();
$acl    = new \Acl\Acl();
//set routes
$routes->setRouter( $router );
$acl->setAclList($aclList);

require_once APP_PATH . 'core/App.php';
require_once APP_PATH . 'core/Template.php';
require_once APP_PATH . 'core/Controller.php';

$app = new App();
$app->setRequest($routes);
$app->run($acl);
