<?php

namespace User\Controller;

use Helpers\ServiceContainer;

/**
 * @Template(module="User")
 */
class UserController extends \Controller{

    public function index(ServiceContainer $serviceContainer){
        $test = array();
        $this->view( 'home/index.html' );
    }

}