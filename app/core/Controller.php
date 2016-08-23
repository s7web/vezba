<?php

/**
 * Created by PhpStorm.
 * User: s7dev
 * Date: 8/18/16
 * Time: 9:14 PM
 */
abstract class Controller
{
	protected function view($name, array $data)
	{
		extract($data);
		require "../app/view/header.php";
		require "../app/view/" . $name . ".php";
		require "../app/view/footer.php";
	}
}