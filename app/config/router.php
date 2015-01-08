<?php
/**
 * Set up routes for framework
 *
 * Give routes in array format
 * Example:
 * $router = array(
 *              array( 'route' => 'our/route', 'controller' => 'our_controller', 'method' => 'our_method')
 * )
 * Where:
 * key 'route' is url which will be given by user
 * key 'controller' is controller which will handle request
 * key 'method' is method which will handle request
 */
$router = array(
    array('route' => 'home/cms', 'controller' => 'home', 'method' => 'index', 'request_method' => 'GET'),
    array('route' => 'home/about-us', 'controller' => 'home', 'method' => 'about', 'request_method' => 'GET'),
    array('route' => 'home/services', 'controller' => 'home', 'method' => 'services', 'request_method' => 'GET'),
    array('route' => 'home/downloads', 'controller' => 'home', 'method' => 'downloads', 'request_method' => 'GET'),
    array('route' => 'home/projects', 'controller' => 'home', 'method' => 'projects', 'request_method' => 'GET'),
    array('route' => 'home/contact', 'controller' => 'home', 'method' => 'contact', 'request_method' => 'GET'),
    array('route' => 'home', 'controller' => 'home', 'method' => 'changeLang', 'request_method' => 'GET'),

);
/** @var \Router\Router $routes */
$routes = new \Router\Router();
//set routes
$routes->setRouter( $router );
