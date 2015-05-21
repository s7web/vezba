<?php
namespace Router;

/**
 * Class Router
 * @package Router
 *
 * @version 1-1-2015
 * @author  s7designcreative
 */
class Router{
    /** @var  array $this->routes */
    public $routes;

    /**
     * Set up routes collection
     *
     * @param array $routes
     */
    public function setRouter(array $routes){
        foreach( $routes as $r ){
            $router = new Route();
            $this->routes[] = $router->setRoute( $r );
        }

    }

    /**
     * Get all routes
     *
     * @return $this
     */
    public function getRouter(){
        return $this;
    }

} 