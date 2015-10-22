<?php
namespace S7D\Core\Routing\Controller;

use S7D\Core\Routing\Controller;

class ErrorController extends Controller {

	public function forbidden() {
		return $this->render([], 403);
	}

	public function notFound() {
		return $this->render([], 404);
	}

	public function serverError() {
		return $this->render([], 500);
	}
}
