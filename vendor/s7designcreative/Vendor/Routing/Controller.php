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

	function __construct($user, $em, $request, $session, $parameters)
	{
		$this->user = $user;
		$this->em = $em;
		$this->request = $request;
		$this->session = $session;
		$this->parameters = $parameters;
	}

    /**
     * Calls a view file from controller
     *
     * @param $view
     * @param array
     *
     * @return Response
     */
    protected function view( $view, $data = array() ) {

		if(! preg_match('/::/', $view)) {
        	$className = get_class($this);
			preg_match('/(.*)Controller.+/', $className, $class);
			$path = $_SERVER['DOCUMENT_ROOT'] . 'src/' . str_replace('\\', '/', $class[1]) . 'views/';
		} else {
			$path = $_SERVER['DOCUMENT_ROOT'] . 'src/' . preg_replace(['/::.*/', '/\\\/'], ['', '/'], $view) . '/views/';
			$view = preg_replace('/.*::/', '', $view);
		}

        $loader = new \Twig_Loader_Filesystem(array(
			$path,
			$_SERVER['DOCUMENT_ROOT'] . 'app/views/',
		));

        $twig = new \Twig_Environment( $loader );
        $twig->addExtension( new \S7D\Vendor\Helpers\MenuExtension() );
        $twig->addExtension( new \Twig_Extension_Debug() );

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

}
