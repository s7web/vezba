<?php

namespace User\Controller;

use Router\Request;

class UserController extends \Controller{

    public function index(Request $request){
        $this->view( 'home/index.html', array() );
    }

}