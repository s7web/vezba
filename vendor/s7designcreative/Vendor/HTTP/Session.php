<?php
namespace S7D\Vendor\HTTP;

class Session {

	function __construct() {
		session_start();
	}

	public function get($key, $default = false) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
	}

	public function set($key, $value) {
		$_SESSION[$key] = $value;
	}

	public function remove($key) {
		unset($_SESSION[$key]);
	}

	public function setFlash($message, $class = '') {
		$this->set('flash', [
			'message' => $message,
			'class'   => $class,
		]);
	}

	public function getFlash() {
		$flash = $this->get('flash');
		$this->remove('flash');
		return $flash;
	}
}