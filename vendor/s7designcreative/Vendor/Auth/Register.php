<?php
namespace S7D\Vendor\Auth;

use Encrypt\Encrypt;

/**
 * Class Register
 * @package Auth
 *
 * @version 11.01.2015
 * @author S7Designcreative
 */
class Register
{

    /** @var array $errors */
    private $errors = array();

    /**
     * Register new user
     *
     * @param array $data
     *
     * @return bool
     */
    public function register( array $data )
    {
        $filtered_data = $this->filterInput( $data );
        $email         = $filtered_data['email'];
        $password      = $this->encryptPassword( $filtered_data['password'] );
        $role          = $filtered_data['role'];

        if ( ! $this->isValidRegistration( $email )) {
            return false;
        }

        if ($this->createUser( $email, $password, $role )) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate email
     *
     * @param $email
     *
     * @return mixed
     */
    private function validateEmail( $email )
    {
        return filter_var( $email, FILTER_VALIDATE_EMAIL );
    }

    private function isValidRegistration( $email )
    {
        $valid_email  = $this->validateEmail( $email );
        $email_exists = $this->userExists( $email );
        if ($valid_email && ! $email_exists) {
            return true;
        } else {
            $this->setError( 'Email is not valid or already exists' );

            return false;
        }
    }

    /**
     * Encrypt password
     *
     * @param string $password
     *
     * @return string mixed
     * @throws \Exception
     */
    private function encryptPassword( $password )
    {
        return Encrypt::encrypt( $password, DEFAULT_ENCRYPTION );
    }

    /**
     * Create activation token
     *
     * @return string
     */
    private function createToken()
    {
        $token = md5( uniqid( rand(), true ) );

        return $token;
    }

    /**
     * Filter input
     *
     * @param array $data
     *
     * @return array
     */
    private function filterInput( array $data )
    {

        $filtered_data = array();
        foreach ($data as $key => $value) {
            $filtered_data[$key] = filter_var( $value, FILTER_SANITIZE_STRING );
        }

        return $filtered_data;
    }

    private function sendConfirmationMail()
    {

    }

    /**
     * Check if email already exists
     *
     * @param string $email
     *
     * @return bool
     */
    private function userExists( $email )
    {
        $user = \User::where( 'email', '=', $email )->count();

        if ($user > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Save new user to database
     *
     * @param string $email
     * @param string $password
     * @param integer $role
     *
     * @return bool
     */
    private function createUser( $email, $password, $role )
    {
        $user           = new \User();
        $user->email    = $email;
        $user->password = $password;
        $user->role     = $role;
        $user->status   = 0;
        $user->token    = $this->createToken();
        if ($user->save()) {
            return true;
        } else {
            return false;
        }

    }

    public function activateUser( $email, $token )
    {


    }

    /**
     * Set error
     *
     * @param string $error
     */
    private function setError( $error )
    {
        $this->errors[] = $error;
    }

    /**
     * Get all registration errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }


} 