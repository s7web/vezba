<?php

require_once '../app/config/site_config.php';
require_once SITE_PATH .'/vendor/autoload.php';

use Symfony\Component\Yaml\Parser;

$yaml   = new Parser();
$router = $yaml->parse( file_get_contents( APP_PATH.'/config/routes.yml' ) );
/** @var \Router\Router $routes */
$routes = new \Router\Router();
//set routes
$routes->setRouter( $router );

require_once APP_PATH . 'core/App.php';
require_once APP_PATH . 'core/Template.php';
require_once APP_PATH . 'core/Controller.php';

$app = new App();
$app->setRequest($routes);
$app->run();
