<?php
namespace S7D\Vendor\HTTP;

class Request {

	public function get($key, $default = false) {
		return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	}

	public function isPost() {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}
}