<?php

/**
 * Class App
 * Init of application
 *
 * @version 10-12-2014
 * @author  s7designcreative
 */
class App {


	protected $controller = "home";

	protected $method = "index";

	protected $params = [];

    /**
     * Construct method
     *
     * Calls parse url method, checks if controller and method exists, if every thing is ok
     * gives new instance of controller, executes method with given params
     */
    public function __construct( \Router\Router $router )
    {
        try {
            $session = new \Session\Session();
            $request = new \Router\Request( $router, $session );

            $route_exists = $request->getExists();
            if ($route_exists) {
                if (is_readable( "../app/controllers/" . $request->getController() . ".php" )) {

                    $this->controller = $request->getController();
                    require_once "../app/controllers/" . $request->getController() . ".php";

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

            $args = new stdClass();
            $args->request = $request;
            $args->session = $session;

            call_user_func( [ $this->controller, $this->method ], $args );
        } catch ( \Exception $e ) {
            $error = $e->getMessage();
            $trace = $e->getTrace();
            require_once SITE_PATH . 'errors.php';
        }
    }
}