<?php
namespace Auth;
use Session\Session;

/**
 * Class Auth
 * @package Auth
 *
 * @version 09.01.2015
 * @author s7designcreative
 */
class Auth {
    /**
     * Login user
     *
     * @param Session $session
     * @param string $email
     * @param string $password
     * @return array|bool
     */
    public static function login(Session $session, $email, $password)
    {

        $login = new Login($session);
        if ($login->login($email, $password)) {
            return TRUE;
        } else {
            return $login->getErrors();
        }
    }

    public static function register( array $data ){

    }

    public static function accExpired(){

    }

} 