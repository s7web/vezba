<?php

/**
 * Created by PhpStorm.
 * User: s7dev
 * Date: 8/18/16
 * Time: 9:35 PM
 */
class HomeModel extends BaseModel
{

	protected $table = 'nasa_tabela';

	public $data = array(
		array('name' => 'Neda', 'lastname' => 'Andric'),
		array('name' => 'Mladen', 'lastname' => 'Jankovic'),
		array('name' => 'Sasa', 'lastname' => 'Sladic')
	);
}