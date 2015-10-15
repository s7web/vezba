<?php
namespace S7D\Vendor\Routing;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use S7D\Vendor\Auth\Entity\User;
use S7D\Vendor\Helpers\Parameter;
use S7D\Vendor\HTTP\Response;
use Symfony\Component\Yaml\Parser;

class Application
{
	public $root;

	public $parameters;

	public $em;

	function __construct( $root ) {
		$this->root = $root;
        $paths  = array( $this->root . '/src', $this->root . '/vendor/s7designcreative/Vendor/Auth/' );
        $config = Setup::createAnnotationMetadataConfiguration( $paths );
        $config->setAutoGenerateProxyClasses(false);
		$config->setProxyDir( $this->root . '/cache');

		$this->parameters = $this->getParams('parameters.yml');
        $this->em = EntityManager::create( $this->parameters->get('database'), $config );
	}

    public function run() {

		$routesArray = $this->getParams('routes.yml')->getAll();

		$router = new Router();
		foreach($routesArray as $name => $route) {
			if(!isset($route['controller']) || !isset($route['method'])) {
				throw new \Exception(sprintf('Route %s missing controller and/or method.', $name));
			}
			$method = isset($route['request_method']) ? $route['request_method'] : '';
			$roles = isset($route['roles']) ? $route['roles'] : '';
			$router->addRoute($name, new Route($route['route'], $route['controller'], $route['method'], $method, $roles));
		}
		$request = new \S7D\Vendor\HTTP\Request();
		$session = new \S7D\Vendor\HTTP\Session();
		$uri = ltrim($_SERVER['REQUEST_URI'], '/');
		$uri = preg_replace('/\?.*/', '', $uri);
		$found = false;
		foreach($router->routes as $route) {
			if(preg_match('/^' . str_replace('/', '\/', $route->pattern) . '$/', $uri, $queryParams)) {
				$controller = $route->controller;
				$action = $route->action;
				array_shift($queryParams);
				$roles = $route->roles;
				$found = true;
				break;
			}
		}
		if(!$found) {
			throw new \Exception('Route doesn\'t exists.');
		}

		if($session->get('auth')) {
			$user = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->find($session->get('auth'));
		} else {
			$user = new User();
			$user->setRoles(['GUEST']);
		}

		if(array_intersect($user->getRoles(), $roles)) {
			$controller = new $controller($user, $this->em, $request, $session, $router, $this->parameters, $this->root);
			$response = call_user_func_array( [ $controller, $action ], array_values($queryParams) );

		} else {
			$response = new Response();
			$response->redirect($this->parameters->get('landing')[$user->getRoles()[0]]);
		}
		if(!$response instanceof Response) {
			throw new \Exception(sprintf('Action %s::%s must return Response object.', get_class($controller), $action));
		}
		$response->out();

	}

	public function getParams($filePattern) {

		$yml = new Parser();
		$data = $yml->parse( file_get_contents( $this->root . '/app/config/' . $filePattern ) );
		$app = isset($data['app']) ? $data['app'] : $this->parameters->get('app');

		$appConfig = $this->root . '/src/S7D/App/' . $app . '/config/' . $filePattern;
		if(file_exists($appConfig)) {
			$data = array_merge($data, $yml->parse(file_get_contents($appConfig)));
		}
		return new Parameter($data);
	}

}