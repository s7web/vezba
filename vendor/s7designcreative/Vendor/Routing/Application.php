<?php
namespace S7D\Vendor\Routing;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Parser;

class Application
{
	public $root;

	public $parameters;

	public $em;

	function __construct( $root ) {
		$this->root = $root;
        $paths  = array( $this->root . '/src' );
        $config = Setup::createAnnotationMetadataConfiguration( $paths );
        $config->setAutoGenerateProxyClasses(false);
		$config->setProxyDir( $this->root . '/cache');

		$this->parameters = $this->getParams('parameters.yml');
        $this->em = EntityManager::create( $this->parameters['database'], $config );
	}

    public function run() {

		$router = $this->getParams('routes.yml');
		$request = new \S7D\Vendor\HTTP\Request();
		$session = new \S7D\Vendor\HTTP\Session();
		$uri = explode('?', $_SERVER['REQUEST_URI']);
		$uri = end($uri);
		$found = false;
		foreach($router as $route) {
			if(preg_match('/^' . str_replace('/', '\/', $route['route']) . '$/', $uri, $queryParams)) {
				$controller = $route['controller'];
				$action = $route['method'];
				array_shift($queryParams);
				$roles = $route['roles'];
				$found = true;
				break;
			}
		}
		if(!$found) {
			throw new \Exception('Route doesn\'t exists.');
		}

		$login = \S7D\Vendor\Auth\Auth::login( $session, $this->em );
		if($session->get('auth')) {
			$user = $this->em->getRepository( 'S7D\Vendor\Auth\Entity\User' )->find($session->get('auth'));
		} else {
			$user = $login->login( $request->get( 'user' ), $request->get( 'password' ) );
		}

		if(array_intersect($user->getRoles(), $roles)) {
			$controller = new $controller($user, $this->em, $request, $session, $this->parameters);
			$response = call_user_func_array( [ $controller, $action ], array_values($queryParams) );

		} else {
			\S7D\Vendor\Response\Response::redirect('login');
		}
		if(!$response instanceof \S7D\Vendor\HTTP\Response) {
			throw new \Exception(sprintf('Action %s::%s must return Response object.', get_class($controller), $action));
		}
		$response->out();

	}

	public function getParams($filePattern) {

		$yml = new Parser();
		$data = $yml->parse( file_get_contents( $this->root . '/app/config/' . $filePattern ) );

		$iteratorDirectory = new \RecursiveDirectoryIterator( $this->root . '/src/');
		/** @var \SplFileInfo[] $iterator */
		$iterator = new \RecursiveIteratorIterator($iteratorDirectory);

		foreach($iterator as $file) {
			if($file->getFilename() === $filePattern) {
				$data = array_merge($data, $yml->parse(file_get_contents($file->getPath() . '/' . $file->getFilename())));
			}
		}
		return $data;
	}

}