<?php

/**
 * Created by PhpStorm.
 * User: s7dev
 * Date: 8/22/16
 * Time: 9:07 PM
 */
class BaseModel
{
	/** @var  PDO */
	private $con;

	public function __construct($db)
	{
		$this->con = $db;
	}

	protected function getConnection(){
		return $this->con;
	}
}