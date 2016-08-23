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

	protected $params = array();

	protected $query = '';

	protected $table = '';

	public function __construct($db)
	{
		$this->con = $db;
	}

	protected function getConnection(){
		return $this->con;
	}

	public function select(array $fields){

		$fields = implode(',', $fields);
		$this->query = "SELECT $fields FROM $this->table";

		return $this;
	}

	public function where($field, $sign, $value){
		$this->params[] = $value;

		$this->query .= " WHERE $field $sign ?";

		return $this;
	}

	public function andWhere($field, $sign, $value){
		$this->params[] = $value;

		$this->query .= " AND $field $sign ?";

		return $this;
	}

	public function orWhere($field, $sign, $value){
		$this->params[] = $value;

		$this->query .= " AND $field $sign ?";

		return $this;
	}

	public function getResults(){

		$stdm = $this->getConnection()->prepare($this->query);
		$stdm->execute($this->params);

		return $stdm->fetchAll(PDO::FETCH_OBJ);
	}

	public function getResult(){

		$stdm = $this->getConnection()->prepare($this->query);
		$stdm->execute($this->params);

		return $stdm->fetch(PDO::FETCH_OBJ);
	}

	public function getSql(){

		return $this->query;
	}
	
	public function joinTable($table1, $table2, $field1, $field2){
		$this->query .= " JOIN $table1 ON $table2.$field2 = $table1.$field1";
		return $this;
	}

	public function paginate($offset) {
		// $this->>query .=
	}
}