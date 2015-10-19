<?php
namespace S7D\Core\Auth;

use S7D\Core\HTTP\Session;

class Auth
{

    /**
     * Login user class container
     *
     * @param Session $session
     *
     * @return Login
     */
    public static function login( Session $session, $entityManager )
    {

        return new Login( $session, $entityManager );
    }

    /**
     * Get the instance of Register class to register user
     *
     * @return Register
     */
    public static function register()
    {
        return new Register();
    }

    public static function accExpired()
    {

    }

} 