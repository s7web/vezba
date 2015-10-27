<?php
namespace S7D\Core\Routing;

use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use S7D\Core\Auth\Entity\User;
use S7D\Core\Helpers\Container;
use S7D\Core\Helpers\LanguageExtension;
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

	protected $root;

	protected $mailer;

	protected $validCSRF = true;

	function __construct(Container $c)
	{
		$this->container = $c;
		$this->em = $c->em;
		$this->mailer = $c->mailer;
		$this->request = $c->request;
		$this->session = $c->session;
		$this->parameters = $c->parameters;
		$this->router = $c->router;
		$this->root = $c->root;
		$this->user = $this->em->getRepository('S7D\Core\Auth\Entity\User')->find($this->session->getAuth());
		if($c->request->isPost() && $c->request->get('CSRFtoken') !== $c->session->getCSRF()) {
			$this->validCSRF = false;
			$logger = new Logger('Invalid CSRF');
			$logger->pushHandler(new StreamHandler($c->root . '/log/invalid_requests.log'));
			$logger->addInfo(vsprintf('%s, %s, %s, %s', [
				$_SERVER['REMOTE_ADDR'],
				$c->user->getEmail(),
				$c->request->getMethod(),
				$_SERVER['HTTP_REFERER'],
			]));
		}
	}

    /**
     * Calls a view file from controller
     *
     * @param $view
     * @param array
     * @param $code
     *
     * @return Response
     */
    protected function view( $view, $data = [], $code = 200) {

		if(preg_match('/::/', $view)) {
			$view = preg_replace('/.*::/', '', $view);
		}

		$appDir = $this->root . '/src/S7D/App/' . $this->parameters->get('app');

        $loader = new \Twig_Loader_Filesystem([
			$appDir . '/views/',
			$this->root . '/app/views/',
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
		$function = new \Twig_SimpleFunction('renderController', function($action) use ($that, $twig) {
			list($cnt, $action) = explode('::', $action);
			$this->changeController($cnt);
			/** @var Response $response */
			$response = $this->container->controller->$action();
			return $response->getOutput();
		});
		$twig->addFunction($function);


		$twig->addExtension(new LanguageExtension($this->container->translations));

        if ($this->parameters->get('debug')) {
            $twig->enableDebug();
        }

        $data['flash'] = $this->session->getFlash();
		$data['user'] = $this->user;

        $response = new Response($twig->render( $view, $data ));
		$response->setCode($code);
		return $response;
    }

	protected function render($data = [], $code = 200) {

		$caller = debug_backtrace()[1];
		preg_match('/(.*)Controller\\\(\w*)Controller$/', $caller['class'], $class);
		$view = $class[2] . '/' . $caller['function'] . '.html.twig';
		return $this->view($view, $data, $code);
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

	protected function notFound() {
		return $this->forward($this->container->errorController, 'notFound');
	}

	protected function forward($controller, $action) {
		$this->changeController($controller);
		return $this->container->controller->$action();
	}

	private function changeController($newController) {
		$this->container->controller = function($c) use ($newController) {
			return new $newController($c);
		};
	}
}