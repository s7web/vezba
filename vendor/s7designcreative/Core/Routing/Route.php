<?php
namespace S7D\Core\Routing;

class Route {

	public $pattern;

	public $controller;

	public $action;

	public $method;

	public $roles;

	function __construct( $pattern, $controller, $action, $method, $roles ) {
		$this->pattern    = $pattern;
		$this->controller = $controller;
		$this->action     = $action;
		$this->method     = $method;
		$this->roles      = $roles;
	}
}