<?php
namespace S7D\Core\Helpers;

class Event {

	/** @var  Container */
	protected $container;

	function __construct( $container ) {
		$this->container = $container;
	}
}
