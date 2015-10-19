<?php
namespace S7D\Core\Router;

/**
 * Class Router
 * @package Router
 *
 * @version 1-1-2015
 * @author  S7Designcreative
 */
class Router
{
    /** @var  array $this ->routes */
    public $routes;

    /**
     * Set up routes collection
     *
     * @param array $routes
     */
    public function setRouter( array $routes )
    {
        $this->routes = $routes;
    }

    /**
     * Get all routes
     *
     * @return $this
     */
    public function getRouter()
    {
        return $this;
    }

} 