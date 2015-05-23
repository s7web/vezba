<?php

use Symfony\Component\Yaml\Parser;

$yaml   = new Parser();
$router = $yaml->parse( file_get_contents( APP_PATH.'/config/routes.yml' ) );
/** @var \Router\Router $routes */
$routes = new \Router\Router();
//set routes
$routes->setRouter( $router );
