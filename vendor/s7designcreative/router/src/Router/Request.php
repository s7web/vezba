<?php
namespace Router;

use Session\Session;

/**
 * Class Request
 *
 * Handles user http request
 *
 * @package Router
 *
 * @version 1-1-2015
 * @author  s7designcreative
 */
class Request
{

    /** @var  string $this ->url */
    public $url;

    /** @var $this ->routes array */
    public $routes;

    /** @var  string $this ->controller */
    private $controller;

    /** @var  string $this ->method */
    private $method;

    /** @var  array $this ->params */
    private $params;

    /** @var  bool $this ->exists */
    private $exists;
    /** @var string $this ->request_method */
    private $request_method;

    public $session;

    private $role;

    /**
     * Set up Request class
     *
     * @param Router $routes
     */
    public function __construct( Router $routes, Session $session )
    {
        $this->url = 'public/index.php/' . $_SERVER['QUERY_STRING'];

        $this->request_method = filter_var( $_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_STRING );

        $this->session = $session;

        $this->routes = $routes->getRouter();

        $this->matchRoute();
    }

    /**
     * Parse url to get user request
     *
     * @return array
     */
    private function parseUrl()
    {

        $url = explode( '/', rtrim( $this->url, '/' ) );

        return $url;
    }

    /**
     * Match defined routes with user request
     *
     * If requested route exists set up $this->exists to bool true
     *
     * Also checks route method and if is POST protects against CSRF
     */
    private function matchRoute()
    {

        $this->exists = false;
        $have_params  = false;

        $route_request = $this->getRequestedRoute();
        $params_check  = strpos( $route_request, '=' );
        if ($params_check != false) {
            $have_params = true;
        }

        foreach ($this->routes->routes as $name => $route) {

            if ($route['route'] === $this->filterRequestedRoute( $have_params )) {
                if (strtoupper( $route['request_method'] ) !== strtoupper( $this->request_method ) &&
                    $route['request_method'] != 'ANY'
                ) {
                    throw new \Exception( 'Request method is not allowed' );
                    break;
                }
                $this->controller = $route['controller'];
                $this->method     = $route['method'];
                $this->params     = array();
                $this->role       = $route['role'];
                if ($have_params) {
                    $this->params = $this->filterRequestedParams();
                }

                $this->exists = true;
            }
        }
        if ($this->url === null) {
            $this->controller = DEFAULT_CTRL;
            $this->method     = DEFAULT_METHOD;
            $this->params     = array();
            $this->exists     = true;
        }
    }

    /**
     * Extract only route without params, for matching
     *
     * @see Request::matchRoute
     *
     * @param bool $have_params
     *
     * @return array|string
     */
    private function filterRequestedRoute( $have_params )
    {
        $route = $this->parseUrl();
        if ($have_params) {
            array_pop( $route );
        }
        $route = filter_var( implode( '/', $route ), FILTER_SANITIZE_URL );

        $route = preg_replace('/.*index.php\//', '', $route);

        return $route;
    }

    /**
     * Get requested url
     *
     * @return string
     */
    private function getRequestedRoute()
    {

        $route = $this->parseUrl();
        $route = implode( '/', $route );

        return $route;
    }

    /**
     * Set parameters if exist
     *
     * @return array
     */
    private function filterRequestedParams()
    {

        $route       = $this->parseUrl();
        $params      = array_pop( $route );
        $real_params = array();

        $params = explode( ';', $params );

        foreach ($params as $param) {
            if ($param === '') {
                continue;
            }
            $real_uf = explode( '=', $param );
            if ( ! array_key_exists( 1, $real_uf ) || $real_uf[1] === '') {
                continue;
            }

            $real_params[filter_var( $real_uf[0], FILTER_SANITIZE_STRING )] = filter_var(
                $real_uf[1],
                FILTER_SANITIZE_STRING
            );
        }

        return $real_params;
    }

    /**
     * Returns requested controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Returns requested method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Return requested params
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Return is route exists
     *
     * @return bool
     */
    public function getExists()
    {
        return $this->exists;
    }

    /**
     * Get all params from GET request
     *
     * @return array
     */
    public function requestAllGet()
    {
        return $this->filterRequestedParams();
    }

    /**
     * Return single param from GET request array, you need to provide key
     *
     * @param $key
     *
     * @return string
     */
    public function requestGetParam( $key )
    {
        $params = $this->filterRequestedParams();
        if (array_key_exists( $key, $params )) {
            return $params[$key];
        } else {
            return '';
        }

    }

    /**
     * Get all values from POST request
     *
     * @return array
     */
    public function getAllPost()
    {
        $post          = $_POST;
        $filtered_post = array();
        foreach ($post as $key => $value) {
            $filtered_post[filter_var( $key, FILTER_SANITIZE_STRING )] = filter_var(
                $value,
                FILTER_SANITIZE_STRING
            );
        }

        return $filtered_post;
    }

    /**
     * Get single param from POST request
     *
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public function getParamPost( $key, $default = '' )
    {

        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    public function csrf_protect()
    {

        $token = md5( uniqid( rand(), true ) );

        $this->session->setSessionKey( 'token', $token );

        return $token;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->request_method;
    }

    /**
     * Get role required for accessing route
     *
     * @return string
     */
    public function getRole() {
        return $this->role;
    }
} 