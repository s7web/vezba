<?php

namespace User\Controller;

use Helpers\ServiceContainer;

/**
 * @Template(module="User")
 */
class UserController extends \Controller{

    public function index(ServiceContainer $serviceContainer){
        $users = $serviceContainer->getEntityManager()->getRepository('User\Entity\UserEntity')->findAll();
        $test = array();
        echo $test['proba'];
        $this->view( 'home/index.html' );
    }

}