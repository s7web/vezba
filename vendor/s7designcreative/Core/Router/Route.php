<?php
namespace S7D\Core\Router;

/**
 * Class Route
 * @package Router
 *
 * @version 1-1-2015
 * @author  S7Designcreative
 */
class Route
{

    /** @var  string */
    public $name;

    /** @var  string $this ->route */
    public $route;

    /** @var  string $this ->controller */
    public $controller;

    /** @var  string $this ->method */
    public $method;

    public $request_method;
    public $role;

    /**
     * Set route
     *
     * @param $name
     * @param array $route
     *
     * @return $this
     * @throws \Exception
     */
    public function setRoute( $name, array $route )
    {

        if (empty( $route )) {
            throw new \Exception( 'Route is empty. Error in configuration' );
        }

        $this->name           = $name;
        $this->route          = $route['route'];
        $this->controller     = $route['controller'];
        $this->method         = $route['method'];
        $this->request_method = $route['request_method'];
        $this->role           = $route['role'];

        return $this;

    }
} 