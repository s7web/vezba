<?php
namespace S7D\Vendor\Routing;

use S7D\Vendor\Helpers\Parameter;
use \S7D\Vendor\HTTP\Response;

class Controller
{
	/** @var  \S7D\Vendor\Auth\Entity\User */
	protected $user;

	/** @var  \Doctrine\ORM\EntityManager */
	protected $em;

	/** @var  \S7D\Vendor\HTTP\Request */
	protected $request;

	/** @var  \S7D\Vendor\HTTP\Session */
	protected $session;

	/** @var  Parameter */
	protected $parameters;

	/** @var  Router */
	protected $router;

	protected $rootDir;

	function __construct($user, $em, $request, $session, $router, $parameters, $rootDir)
	{
		$this->user = $user;
		$this->em = $em;
		$this->request = $request;
		$this->session = $session;
		$this->parameters = $parameters;
		$this->router = $router;
		$this->rootDir = $rootDir;
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
        $twig->addExtension( new \S7D\Vendor\Helpers\MenuExtension() );
        $twig->addExtension( new \Twig_Extension_Debug() );

		$router = $this->router;

		$function = new \Twig_SimpleFunction('path', function($routeName, $id = null) use ($router){
			return $router->generateUrl($this->parameters->get('url'), $routeName, $id);
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
		$url = $this->router->generateUrl($this->parameters->get('url'), $route, $id);
		return $this->redirect($url);
	}

	protected function redirectBack(){
		return $this->redirect($_SERVER['HTTP_REFERER']);
	}
}
