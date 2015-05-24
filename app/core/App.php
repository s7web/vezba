<?php

/**
 * Class App
 * Init of application
 *
 * @version 10-12-2014
 * @author  s7designcreative
 */

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class App
{

    protected $controller = "home";

    protected $method = "index";

    protected $params = [ ];

    /**
     * Construct method
     *
     * Calls parse url method, checks if controller and method exists, if every thing is ok
     * gives new instance of controller, executes method with given params
     * Also boots a Doctrine2 ORM, and creates ServiceContainer for Session and Request objects
     *
     * @param \Router\Router $router
     */
    public function __construct( \Router\Router $router )
    {
        try {
            $session = new \Session\Session();
            $request = new \Router\Request( $router, $session );

            $logger = new \Monolog\Logger( 'app_level_logs' );
            $logger->pushHandler(
                new \Monolog\Handler\StreamHandler( SITE_PATH.'log/app.log', \Monolog\Logger::DEBUG )
            );

            $route_exists = $request->getExists();
            if ($route_exists) {
                $this->controller = $request->getController();
                if (class_exists( $this->controller )) {

                    $this->controller = new $this->controller;

                } else {
                    throw new \Exception( 'Such controller does not exists!' );
                }
            } else {
                throw new \Exception( 'Such route does not exist!' );
            }

            if ($route_exists) {

                if (method_exists( $this->controller, $request->getMethod() )) {

                    $this->method = $request->getMethod();

                } else {
                    throw new \Exception( 'Such method does not exist!' );
                }
            } else {
                throw new \Exception( 'Such route does not exist!' );
            }

            $paths     = array( SITE_PATH.'/src' );
            $isDevMode = false;

            $dbParams = array(
                'host'     => DATABASE_HOST,
                'driver'   => DATABASE_DRIVER,
                'user'     => DATABASE_USERNAME,
                'password' => DATABASE_PASSWORD,
                'dbname'   => DATABASE_NAME,
            );

            $config = Setup::createAnnotationMetadataConfiguration( $paths, $isDevMode );

            $entityManager    = EntityManager::create( $dbParams, $config );
            $serviceContainer = new \Helpers\ServiceContainer( $request, $entityManager, $logger );

            call_user_func( [ $this->controller, $this->method ], $serviceContainer );
        } catch ( \Exception $e ) {
            $error = $e->getMessage();
            $trace = $e->getTrace();
            $logger->addDebug(
                'Error has occurred in application, exception has been thrown. Route called
             '.$_SERVER['REQUEST_URI'].' | Error: '.$error,
                array( $request->getParams() )
            );
            require_once SITE_PATH.'errors.php';
        }
    }
}