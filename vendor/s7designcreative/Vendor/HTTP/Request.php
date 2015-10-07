<?php
namespace S7D\Vendor\HTTP;

use S7D\Vendor\Helpers\ArrayDot;

class Request {

	public function get($key, $default = false) {
		return ArrayDot::get($_REQUEST, $key, $default);
	}

	public function getAll() {
		return $_REQUEST;
	}

	public function isPost() {
		return $this->getMethod() === 'POST';
	}

	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}
}