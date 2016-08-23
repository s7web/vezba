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
		
		/** @var HomeModel $data */
		$data = $c->databaseLoader->getModel('HomeModel');
		$this->view('home/index', array('hack' => $data->data) );
	}

}