<?php
namespace S7D\Core\Helpers;

class Parameter {

	private $data = [];

	function __construct( $data ) {
		$this->data = $data;
	}

	/**
	 * @param $key
	 * @param null $default
	 * @return Parameter|string|array
	 */
	public function get($key, $default = null) {
		return ArrayDot::get($this->data, $key, $default);
	}

	public function getAll() {
		return $this->data;
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function add($key, $value) {
		$this->data[$key] = $value;
	}
}