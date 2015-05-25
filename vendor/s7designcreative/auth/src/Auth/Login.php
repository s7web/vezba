<?php
namespace Auth;

use Encrypt\Encrypt;
use Session\Session;

/**
 * Class Login
 * @package Auth
 *
 * @version 09.01.2015
 * @author s7designcreative
 */
class Login
{
    /** @var Session $this ->session */
    private $session;

    /**
     * @var
     */
    private $entityManager;

    /**
     * @var array
     */
    private $errors = array();

    /**
     * Set up class properties
     *
     * @param Session $session
     * @param $entityManager
     */
    public function __construct( Session $session, $entityManager )
    {
        $this->session = $session;

        $this->entityManager = $entityManager;
    }

    /**
     * Log in user
     *
     * @param string $email
     * @param string $password
     *
     * @return bool
     */
    public function login( $email, $password )
    {
        $email       = $this->filterEmail( $email );
        $password    = $this->preparePassword( $password );
        $user        = $this->findUser( $email, $password );
        $user_active = $this->checkIsActive( $user );
        if ( ! $user) {
            $this->setError( 'Wrong email/password' );

            return false;
        }

        if ( ! $user_active) {
            return false;
        }
        $this->session->set_after_login( $user->id );

        return true;
    }

    /**
     * Check email, password against database
     *
     * @param string $email
     * @param string $password
     *
     * @return mixed
     */
    private function findUser( $email, $password )
    {
        return array();
    }

    /**
     * Checks is user acc banned and is active (  email is confirmed )
     *
     * @param \User $user
     *
     * @return bool
     */
    private function checkIsActive( $user )
    {
        $user_active = false;
        $banned      = $this->isBanned( $user );
        if ($user->status === 1) {
            $user_active = true;
            $this->setError( 'Account is not active, please confirm your email, or contact administrator.' );
        }
        if ($banned) {
            $user_active = false;
            $this->setError( 'Account is banned! ' );
        }

        return $user_active;
    }

    /**
     * Check is user banned
     *
     * @param \User $user
     *
     * @return bool
     */
    private function isBanned( $user )
    {

        $banned = false;

        if ($user->role === 5) {
            $banned = true;
        }

        return $banned;
    }

    /**
     * Filter user email
     *
     * @param $email
     *
     * @return mixed
     */
    private function filterEmail( $email )
    {
        return filter_var( $email, FILTER_SANITIZE_EMAIL );
    }

    /**
     * Filter password
     *
     * @param $password
     *
     * @return mixed
     */
    private function filterPassword( $password )
    {
        return filter_var( $password, FILTER_SANITIZE_STRING );
    }

    /**
     * Get user object
     * @return \Illuminate\Support\Collection|static
     */
    public function getUser()
    {
        $user_id = (int) $this->session->getSessionKey( 'user_login' );

        return \User::find( $user_id );
    }

    /**
     * Return filtered and encrypted password
     *
     * @param $password
     *
     * @return mixed
     * @throws \Exception
     */
    private function preparePassword( $password )
    {
        return Encrypt::encrypt( $this->filterPassword( $password ), DEFAULT_ENCRYPTION );
    }

    /**
     * Set error
     *
     * @param $error
     */
    private function setError( $error )
    {
        $this->errors[] = $error;
    }

    /**
     * Return errors occurred on login
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
} 