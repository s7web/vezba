<?php
namespace S7D\Vendor\Routing;

class Router {

	/** @var Route[]  */
	public $routes = [];

	public function addRoute($name, $route) {
		$this->routes[$name] = $route;
	}

	public function generateUrl($baseUrl, $name, $id = null) {
		if(!isset($this->routes[$name])) {
			throw new \Exception(sprintf('Route \'%s\' is not defined.', $name));
		}
		return $baseUrl . '?' . preg_replace('/\(.*\)/', $id, $this->routes[$name]->pattern);
	}
}