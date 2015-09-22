<?php

/**
 * Class App
 * Init of application
 *
 * @version 10-12-2014
 * @author  S7Designcreative
 */

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use S7D\Vendor\Acl\Acl;

class App
{
    /**
     * @var S7D\Vendor\Router\Request
     */
    protected $request;

    /** @var EntityManager */
    public $entityManager;

    public function __construct(){
        $paths     = array( SITE_PATH.'/src' );
        $isDevMode = false;

        $dbParams = array(
            'host'     => DATABASE_HOST,
            'driver'   => DATABASE_DRIVER,
            'port'     => DATABASE_PORT,
            'user'     => DATABASE_USERNAME,
            'password' => DATABASE_PASSWORD,
            'dbname'   => DATABASE_NAME,
        );

        $config = Setup::createAnnotationMetadataConfiguration( $paths, $isDevMode );
        $config->setAutoGenerateProxyClasses(false);
		$config->setProxyDir(SITE_PATH . '/cache');

        $this->entityManager = EntityManager::create( $dbParams, $config );
    }
    /**
     * Calls parse url method, checks if controller and method exists, if every thing is ok
     * gives new instance of controller, executes method with given params
     * Also boots a Doctrine2 ORM, and creates ServiceContainer for Session and Request objects
     * @param S7D\Vendor\Router\Router $router
     * @throws Exception
     */
    public function setRequest(S7D\Vendor\Router\Router $router) {

        $session = new S7D\Vendor\Session\Session();
        $request = new S7D\Vendor\Router\Request( $router, $session );
        $route_exists = $request->getExists();
        if ($route_exists) {
            $this->controller = $request->getController();
            if (!class_exists( $this->controller )) {

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
		$logger = new \Monolog\Logger( 'app_level_logs' );
		$logger->pushHandler(
			new \Monolog\Handler\StreamHandler( SITE_PATH.'log/app.log', \Monolog\Logger::DEBUG )
		);
		$route_roles = $this->request->roles;
		$login = \S7D\Vendor\Auth\Auth::login( $this->request->session, $this->entityManager );
		if(isset($_SESSION['auth']) && $_SESSION['auth']) {
			$user = $login->getUser();
		} else {
			$username = $this->request->getParamPost( 'user' );
			$password= $this->request->getParamPost( 'password' );
			$user = $login->login( $username, $password );
		}
		if(! in_array('GUEST', $route_roles)){
			if(in_array('SUPER_ADMIN', $user->getRoles())) {
				if(!preg_match('/\/all-users/', $this->request->url)) {
					return \S7D\Vendor\Response\Response::redirect('all-users');
				}
			}
			if(!$user || ( $user && ! array_intersect($route_roles, $user->getRoles()))) {
				return \S7D\Vendor\Response\Response::redirect('login');
			}
			if($user->getStatus() == 0 && !preg_match('/\/registered$/', $this->request->url)) {
				return \S7D\Vendor\Response\Response::redirect('registered');
			}
		}
		$serviceContainer = new \S7D\Vendor\Helpers\ServiceContainer( $this->request, $this->entityManager, $logger );
		$this->controller = new $this->controller($user, $this->entityManager);
		call_user_func( [ $this->controller, $this->method ], $serviceContainer );
	}

}