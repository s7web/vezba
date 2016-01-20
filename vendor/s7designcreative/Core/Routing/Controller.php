<?php
namespace S7D\Core\Routing;

use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use S7D\Core\Auth\Repository\UserMetaRepository;
use S7D\Core\Helpers\Repository\SiteOptionRepository;
use S7D\Vendor\Blog\Twig\TextTransition;
use S7D\Core\Auth\Entity\User;
use S7D\Core\Auth\Repository\UserRepository;
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

	protected $logger;

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
		$this->logger = new Logger('Dev');
		$this->logger->pushHandler(new StreamHandler($c->root . '/log/dev.log'));
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

		$appDir = $this->root . 'src/S7D/App/' . $this->parameters->get('app');

        $loader = new \Twig_Loader_Filesystem([
			$appDir . '/views/',
			$this->root . 'app/views/',
		]);

        $twig = new \Twig_Environment( $loader );
        $twig->addExtension( new \S7D\Core\Helpers\MenuExtension() );
        $twig->addExtension( new \Twig_Extension_Debug() );

		$router = $this->router;

		$function = new \Twig_SimpleFunction('path', function($routeName, $params = null) use ($router){
			return $router->generateUrl($this->parameters->get('url'), $routeName, $params);
		});
		$twig->addFunction($function);

		$function = new \Twig_SimpleFunction('CSRFinput', function($reload = false) {

			$this->session->generateCSRF();

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

		$function = new \Twig_SimpleFunction('getOption', function($key, $default)  {
			return $this->getOption($key, $default);
		}, ['is_safe' => ['html']]);
		$twig->addFunction($function);

		$filter = new \Twig_SimpleFilter('unserialize', 'unserialize');
		$twig->addFilter($filter);

		$twig->addExtension(new LanguageExtension($this->container->translations));
		$twig->addExtension(new TextTransition($this->session->get('textScript', $this->parameters->get('textScript'))));

        if ($this->parameters->get('debug')) {
            $twig->enableDebug();
        }

        $data['flash'] = $this->session->getFlash();
		$data['user'] = $this->user;
		$data['parameters'] = $this->parameters;
		$data['site'] = [
			'language' => $this->session->get('language', $this->parameters->get('language', 'en')),
			'textScript' => $this->session->get('textScript', $this->parameters->get('textScript', 'latin')),
		];

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

	protected function translate($term) {
		return $this->container->translations->get($term, $term);
	}

	/**
	 * @return UserRepository
	 */
	protected function getUserRepo() {
		return $this->em->getRepository('S7D\Core\Auth\Entity\User');
	}

	/**
	 * @return UserMetaRepository
	 */
	protected function getUserMetaRepo() {
		return $this->em->getRepository('S7D\Core\Auth\Entity\UserMeta');
	}

	/**
	 * @return SiteOptionRepository
	 */
	protected function getSiteOptionRepo() {
		return $this->em->getRepository('S7D\Core\Helpers\Entity\SiteOption');
	}


	protected function getOption($key, $default = null) {
		return $this->getSiteOptionRepo()->get($key, $default);
	}

	protected function setOption($key, $value) {
		$this->getSiteOptionRepo()->set($key, $value);
	}

	protected function getUserMeta($user, $key) {
		return $this->getUserMetaRepo()->get($user, $key);
	}

	protected function setUserMeta($user, $key, $value) {
		$this->getUserMetaRepo()->set($user, $key, $value);
	}

	protected function getCurrentUserMeta($key) {
		return $this->getUserMeta($this->user, $key);
	}

	protected function setCurrentUserMeta($key, $value) {
		$this->setUserMeta($this->user, $key, $value);
	}
}
