<?php
namespace S7D\Core\Routing;

use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use S7D\Core\Auth\Entity\User;
use S7D\Core\Helpers\Parameter;
use S7D\Core\HTTP\Request;
use \S7D\Core\HTTP\Response;
use S7D\Core\HTTP\Session;

class Controller
{
	/** @var  \S7D\Core\Auth\Entity\User */
	protected $user;

	/** @var  \Doctrine\ORM\EntityManager */
	protected $em;

	/** @var  \S7D\Core\HTTP\Request */
	protected $request;

	/** @var  \S7D\Core\HTTP\Session */
	protected $session;

	/** @var  Parameter */
	protected $parameters;

	/** @var  Router */
	protected $router;

	protected $rootDir;

	protected $validCSRF = true;

	function __construct(User $user, EntityManager $em, Request $request, Session $session, Router $router, Parameter $parameters, $rootDir)
	{
		$this->user = $user;
		$this->em = $em;
		$this->request = $request;
		$this->session = $session;
		$this->parameters = $parameters;
		$this->router = $router;
		$this->rootDir = $rootDir;
		if($request->isPost() && $request->get('CSRFtoken') !== $session->getCSRF()) {
			$this->validCSRF = false;
			$logger = new Logger('Invalid CSRF');
			$logger->pushHandler(new StreamHandler($rootDir . '/log/invalid_requests.log'));
			$logger->addInfo(vsprintf('%s, %s, %s, %s', [
				$_SERVER['REMOTE_ADDR'],
				$user->getEmail(),
				$request->getMethod(),
				$_SERVER['HTTP_REFERER'],
			]));
		}
	}

    /**
     * Calls a view file from controller
     *
     * @param $view
     * @param array
     *
     * @return Response
     */
    protected function view( $view, $data = [] ) {

		if(preg_match('/::/', $view)) {
			$view = preg_replace('/.*::/', '', $view);
		}

        $loader = new \Twig_Loader_Filesystem([
			$this->rootDir . '/src/S7D/App/' . $this->parameters->get('app') . '/views/',
			$this->rootDir . '/app/views/',
		]);

        $twig = new \Twig_Environment( $loader );
        $twig->addExtension( new \S7D\Core\Helpers\MenuExtension() );
        $twig->addExtension( new \Twig_Extension_Debug() );

		$router = $this->router;

		$function = new \Twig_SimpleFunction('path', function($routeName, $id = null) use ($router){
			return $router->generateUrl($this->parameters->get('url'), $routeName, $id);
		});
		$twig->addFunction($function);

		$function = new \Twig_SimpleFunction('CSRFinput', function($reload = false) {
			if($reload) {
				$this->session->generateCSRF();
			}
			return sprintf('<input type="hidden" name="CSRFtoken" value="%s">', $this->session->getCSRF() );
		});
		$twig->addFunction($function);

		$that = $this;
		$function = new \Twig_SimpleFunction('renderController', function($action) use ($that, $twig){
			list($cnt, $action) = explode('::', $action);
			$cnt = new $cnt($that->user, $that->em, $that->request, $that->session, $that->router, $that->parameters, $that->rootDir);
			/** @var Response $response */
			$response = $cnt->$action();
			return $response->getOutput();
		});
		$twig->addFunction($function);

        if ($this->parameters->get('debug')) {
            $twig->enableDebug();
        }

        $data['flash'] = $this->session->getFlash();
		$data['user'] = $this->user;

        return new Response($twig->render( $view, $data ));
    }

	protected function render($data = []) {

		$caller = debug_backtrace()[1];
		preg_match('/(.*)Controller\\\(\w*)Controller$/', $caller['class'], $class);
		$view = $class[2] . '/' . $caller['function'] . '.html.twig';
		return $this->view($view, $data);
	}

	protected function redirect($url) {
		$response = new Response();
		$response->redirect($url);
		return $response;
	}

	protected function redirectRoute($route, $id = null) {
		$url = $this->generateUrl($route, $id);
		return $this->redirect($url);
	}

	protected function redirectBack(){
		return $this->redirect($_SERVER['HTTP_REFERER']);
	}

	protected function generateUrl($route, $id = null) {
		return $this->router->generateUrl($this->parameters->get('url'), $route, $id);
	}
}
