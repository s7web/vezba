<?php
namespace S7D\Vendor\Helpers;

class Parameter {

	private $data = [];

	function __construct( $data ) {
		$this->data = $data;
	}

	public function get($key, $default = false) {
		return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
	}

	public function getAll() {
		return $this->data;
	}
}