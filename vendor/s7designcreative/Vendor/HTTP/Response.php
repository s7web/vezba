<?php
namespace S7D\Vendor\HTTP;

class Response {

	protected $output;

	protected $code;

	protected $contentType;

	function __construct($output, $contentType = ':', $code = 200) {
		$this->output = $output;
		$this->contentType = $contentType;
		$this->code = $code;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function out() {
		header($this->contentType, true, $this->code);
		echo $this->output;
	}

	public static function redirectBack(){
		self::redirect($_SERVER['HTTP_REFERER']);
	}

	public static function redirect($url) {
		header( 'Location: '.$url, true, 301 );
		exit();
	}
}
