<?php
namespace S7D\Core\Helpers;

/**
 * Dependency Injection Container, slightly changed http://twittee.org/
 *
 * Class Container
 * @package S7D\Core\Helpers
 */
class Container {

	protected $services = [];

	public function __set($id, $service) {
		$this->services[$id] = $service;
	}

	public function __get($id) {
		if(! $this->has($id)) {
			throw new \Exception(sprintf('Service \'%s\' not defined.', $id));
		}
		return $this->services[$id]($this);
	}

	public function has($id) {
		return isset($this->services[$id]);
	}

}
