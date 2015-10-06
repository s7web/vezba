<?php
namespace S7D\Vendor\Routing;

class Route {

	public $name;

	public $pattern;

	public $controller;

	public $action;

	public $method;

	public $roles;

	function __construct( $name, $pattern, $controller, $action, $method, $roles ) {
		$this->name       = $name;
		$this->pattern    = $pattern;
		$this->controller = $controller;
		$this->action     = $action;
		$this->method     = $method;
		$this->roles      = $roles;
	}
}