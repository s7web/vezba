<?php
namespace S7D\Core\Routing;

class Router {

	/** @var Route[]  */
	public $routes = [];

	public function addRoute($name, $route) {
		$this->routes[$name] = $route;
	}

	public function generateUrl($baseUrl, $name, $params) {
		if(!isset($this->routes[$name])) {
			throw new \Exception(sprintf('Route \'%s\' is not defined.', $name));
		}
		if(!is_array($params)) {
			$params = [$params];
		}
		return $baseUrl . preg_replace_callback('/\(.*\)/U', function() use (&$params) {
			return array_shift($params);
		}, $this->routes[$name]->pattern);
	}
}
