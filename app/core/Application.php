<?php

class Application
{

	public function run()
	{
		$c = new Container();
		$url = '';
		if(isset( $_REQUEST['url'] )){
			$url = $_REQUEST['url'];
		}else{
			$url = 'home/index';
		}


		$params = explode('/', $url);

		$controllerFile = "../app/controllers/" . ucfirst($params[0]) . "Controller.php";

		$controller = '';
		if(file_exists($controllerFile)){
			require $controllerFile;
			$controller = ucfirst($params[0]) . "Controller";
		}else{
			$controller = "HomeController";
			require "../app/controllers/HomeController.php";
		}

		$controllerObj = new $controller();


		$method = '';

		if(isset($params[1]) && method_exists($controllerObj,$params[1])){
			$method = $params[1];
		}else{
			$method = 'index';
		}
		$database = new PDO('mysql:host=localhost; dbname=vezba', 'root', 'root');
		$c->databaseConnection = function () use($database){
			return $database;
		};
		$databaseLoader = new Database($c->databaseConnection);
		$c->databaseLoader = function () use($databaseLoader){
			return $databaseLoader;
		};


		call_user_func_array(array($controllerObj, $method), array($c));

	}
}