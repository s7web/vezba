<?php

/**
 * Created by PhpStorm.
 * User: s7dev
 * Date: 8/18/16
 * Time: 8:52 PM
 */
class HomeController extends Controller
{

	public function index($c)
	{
		echo $_GET['bla'];
		/** @var HomeModel $data */
		$data = $c->databaseLoader->getModel('HomeModel');
		$result = $data->select(array('*'))->where('title', '=', 'Naslov1')->getResults();
		$this->view('home/index', array('hack' => $result) );
	}

}