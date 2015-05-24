<?php
namespace Router;

/**
 * Class Route
 * @package Router
 *
 * @version 1-1-2015
 * @author  s7designcreative
 */
class Route {

    /** @var  string $this->route */
    public $route;

    /** @var  string $this->controller */
    public $controller;

    /** @var  string $this->method */
    public $method;

    public $request_method;

    /**
     * Set route
     *
     * @param array $route
     * @return $this
     * @throws \Exception
     */
    public function setRoute( array $route )
    {

        if (empty( $route )) {
            throw new \Exception( 'Route is empty. Error in configuration' );
        }

        $this->route = $route[ 'route' ];
        $this->controller = $route[ 'controller' ];
        $this->method = $route[ 'method' ];
        $this->request_method = $route['request_method'];

        return $this;

    }
} 