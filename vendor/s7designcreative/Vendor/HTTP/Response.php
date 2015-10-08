<?php
namespace S7D\Vendor\HTTP;

class Response {

	protected $output;

	protected $code;

	protected $contentType;

	protected $redirect;

	function __construct($output = null, $contentType = ':', $code = 200) {
		$this->output = $output;
		$this->contentType = $contentType;
		$this->code = $code;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function out() {
		header($this->contentType, true, $this->code);
		if($this->code === 301) {
			exit();
		}
		echo $this->output;
	}

	public function redirect($url) {
		$this->contentType = 'Location: '.$url;
		$this->code = 301;
	}
}
