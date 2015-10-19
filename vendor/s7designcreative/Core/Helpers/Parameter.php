<?php
namespace S7D\Core\Helpers;

class Parameter {

	private $data = [];

	function __construct( $data ) {
		$this->data = $data;
	}

	public function get($key, $default = null) {
		return ArrayDot::get($this->data, $key, null);
	}

	public function getAll() {
		return $this->data;
	}
}