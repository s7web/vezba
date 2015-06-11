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
    protected $request;

    /** @var EntityManager */
    public $entityManager;

    public function __construct(){
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

        $this->entityManager = EntityManager::create( $dbParams, $config );
    }
    /**
     * Calls parse url method, checks if controller and method exists, if every thing is ok
     * gives new instance of controller, executes method with given params
     * Also boots a Doctrine2 ORM, and creates ServiceContainer for Session and Request objects
     * @param \Router\Router $router
     * @throws Exception
     */
    public function setRequest(\Router\Router $router) {

        $session = new \Session\Session();
        $request = new \Router\Request( $router, $session );
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

        $this->request = $request;
    }

    public function run( )
    {
        try {
            $logger = new \Monolog\Logger( 'app_level_logs' );
            $logger->pushHandler(
                new \Monolog\Handler\StreamHandler( SITE_PATH.'log/app.log', \Monolog\Logger::DEBUG )
            );
            $serviceContainer = new \Helpers\ServiceContainer( $this->request, $this->entityManager, $logger );

            $route_role = $this->request->role;

            $login = \Auth\Auth::login( $this->request->session, $this->entityManager );
            if($this->request->session->is_logged()) {
                $user = $login->getUser();
            } else {
                $username = $this->request->getParamPost( 'user' );
                $password= $this->request->getParamPost( 'password' );
                $user = $login->login( $username, $password );
            }

            if($route_role !== 'GUEST'){
                if(!$user || ( $user && !in_array($route_role, $user->getRoles()))) {
                    return \Response\Response::redirect('/public/login');
                }
            }
            call_user_func( [ $this->controller, $this->method ], $serviceContainer );
        } catch ( \Exception $e ) {
            $error = $e->getMessage();
            $logger->addDebug(
                'Error has occurred in application, exception has been thrown. Route called
             '.$_SERVER['REQUEST_URI'].' | Error: '.$error,
                array( $this->request->getParams() )
            );
            require_once SITE_PATH.'errors.php';
        }
    }
}