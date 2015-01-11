<?php
namespace Auth;
use Session\Session;

/**
 * Class Auth
 * @package Auth
 *
 * @version 11.01.2015
 * @author s7designcreative
 */
class Auth {

    /**
     * Login user class container
     *
     * @param Session $session
     * @return Login
     */
    public static function login(Session $session)
    {

        return new Login($session);
    }

    public static function register( array $data ){

    }

    public static function accExpired(){

    }

} 