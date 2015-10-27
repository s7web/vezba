<?php
namespace S7D\Core\Routing;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use S7D\Core\Auth\Entity\Role;
use S7D\Core\Auth\Entity\User;
use S7D\Core\Helpers\Container;
use S7D\Core\Helpers\Parameter;
use S7D\Core\HTTP\Response;
use Symfony\Component\Yaml\Parser;

class Application
{
	public $root;

	/** @var Parameter */
	public $parameters;

	public $em;

	public $container;

	function __construct( $root ) {
		$this->root = $root;
		$that = $this;
		$c = new Container();
		$c->root = function() use ($that) { return $that->root; };
		$c->parameters = function($c) use ($that) {
			return $that->getParams('parameters.yml');
		};
        $c->em = function($c) {

			$packages = $c->parameters->get('packages', []);
			$paths = [];
			$paths[] = $this->root . '/vendor/s7designcreative/Core/';
			foreach($packages as $package) {
				$paths[] = $this->root . '/vendor/s7designcreative/Vendor/' . $package;
			}
			$paths[] = $this->root . '/src/S7D/App/' . $c->parameters->get('app');

			$config = Setup::createAnnotationMetadataConfiguration( $paths );
			$config->setAutoGenerateProxyClasses(false);
			$config->setProxyDir( $this->root . '/cache');

			return EntityManager::create( $c->parameters->get('database'), $config );
		};
		$this->container = $c;
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

		$session = new \S7D\Core\HTTP\Session();

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
		if($session->getAuth()) {
			$user = $this->container->em->getRepository( 'S7D\Core\Auth\Entity\User' )->find($session->getAuth());
		} else {
			$user = new User();
			$role = new Role();
			$role->name = 'GUEST';
			$user->setRoles([$role]);
		}
		$errorController = __NAMESPACE__ . '\Controller\ErrorController';
		if(! $found) {
			if($this->container->parameters->get('debug')) {
				throw new \Exception('Route doesn\'t exists.');
			}
			$controller = $errorController;
			$action = 'notFound';
		} elseif(! array_intersect($user->getRoles(), $roles)){
			if($this->container->parameters->get('debug')) {
				throw new \Exception('This role can\'t access this resource.');
			}
			$controller = $errorController;
			$action = 'forbidden';
		}

		$this->container->request = function() { return new \S7D\Core\HTTP\Request(); };
		$this->container->user = function() use ($user) { return $user; };
		$this->container->session = function() use ($session) { return $session; };
		$this->container->router = function() use ($router) { return $router; };

		$this->container->errorController = function($c) use ($errorController) {
			return new $errorController($c);
		};

		$that = $this;

		$this->container->translations = function($c) use ($that) {
			$language = $c->session->get(
				'lang',
				$c->parameters->get('lang', 'DE')
			);
			return $that->getParams($language . '.yml', 'translations');
		};

		$this->container->mailer = function($c) {
			$transport = \Swift_SmtpTransport::newInstance($c->parameters->get('email.host'), $c->parameters->get('email.port'))
				 ->setUsername($c->parameters->get('email.username'))
				 ->setPassword($c->parameters->get('email.password'))
			;
			return \Swift_Mailer::newInstance($transport);
		};

		$this->container->controller = function($c) use ($controller) {
			return new $controller($c);
		};
		$response = call_user_func_array( [ $this->container->controller , $action ], array_values($queryParams) );

		if(! $response instanceof Response) {
			if($this->container->parameters->get('debug')) {
				throw new \Exception(sprintf('Action %s::%s must return Response object.', $controller, $action));
			}
			$response = call_user_func( [ $this->container->errorController, 'serverError' ] );
		}
		$response->out();

	}

	public function getParams($filePattern, $dir = 'config') {

		$yml = new Parser();
		$configDir = $this->root . '/app/' . $dir . '/' . $filePattern;
		$data = [];
		if(file_exists($configDir)) {
			$data = $yml->parse( file_get_contents($configDir) );
		}
		$app = isset($data['app']) ? $data['app'] : $this->container->parameters->get('app');

		$appConfig = $this->root . '/src/S7D/App/' . $app . '/' . $dir . '/' . $filePattern;
		if(file_exists($appConfig)) {
			$data = array_merge($data, $yml->parse(file_get_contents($appConfig)));
		}
		return new Parameter($data);
	}

}
