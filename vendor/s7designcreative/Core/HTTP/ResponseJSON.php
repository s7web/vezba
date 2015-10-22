<?php
namespace S7D\Core\HTTP;

class ResponseJSON extends Response {

	function __construct($data) {
		$this->output = json_encode($data);
		$this->contentType = 'Content-type: application/json';
	}
}