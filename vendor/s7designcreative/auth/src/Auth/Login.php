<?php
namespace Auth;

use Encrypt\Encrypt;
use s7designcreative\Auth\Entity\User;
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
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function login( $username, $password )
    {
        require_once(__DIR__.'/Entity/User.php');

        $user = $this->entityManager->getRepository( 's7designcreative\Auth\Entity\User' )->findOneBy(array(
            'username' => $username,
        ));
        if ( ! $user) {
            return new User();
        } else {
            if (! password_verify($password, $user->getPassword())) {
                return new User();
            }
        }
        $_SESSION['auth'] = $user->getId();

        return $user;
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
     * Get current user object
     *
     * @return User
     */
    public function getUser()
    {
        $user_id = (int) $_SESSION['auth'];
        require_once(__DIR__.'/Entity/User.php');
        return $this->entityManager->getRepository( 's7designcreative\Auth\Entity\User' )->find($user_id);
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