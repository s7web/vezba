<?php

/**
 * Created by PhpStorm.
 * User: s7dev
 * Date: 8/22/16
 * Time: 9:04 PM
 */
class Database
{

	protected $con;
	
	public function __construct($db)
	{
		$this->con = $db;
	}
	
	public function getModel($name){
		require "../app/model/" . $name . ".php";

		return new $name($this->con);
	}
}