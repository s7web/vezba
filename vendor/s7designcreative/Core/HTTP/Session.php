<?php
namespace S7D\Core\HTTP;

use S7D\Core\Helpers\ArrayDot;

class Session {

	function __construct() {
		session_start();
	}

	public function get($key, $default = false) {
		return ArrayDot::get($_SESSION, $key, $default);
	}

	public function getAll() {
		return $_SESSION;
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

	public function generateCSRF() {
		$token = md5(uniqid());
		$this->set('CSRFtoken', $token);
		return $token;
	}

	public function getCSRF() {
		return $this->get('CSRFtoken') ? $this->get('CSRFtoken') : $this->generateCSRF();
	}

	public function setAuth($id) {
		$this->set('auth', $id);
	}

	public function getAuth() {
		return $this->get('auth');
	}

}
