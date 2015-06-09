<?php
namespace Session;

/**
 * Class Session
 * @package Session
 *
 * @version 4-1-2015
 * @author  s7designcreative
 */
class Session extends SecureSession
{

    /**
     * Start session, chenge name to site name, check is session valid
     */
    public function __construct()
    {
        //session_name( SITE_NAME );
        if (session_id() == '') {
            session_start();
        }
        $this->confirm_session_valid();
    }

    /**
     * Destroy session
     */
    public function session_end()
    {
        session_unset();
        session_destroy();

    }

    /**
     * Check is ip address matches
     * @return bool
     */
    private function request_ip_matches()
    {
        if ( ! array_key_exists( 'ip', $_SESSION ) || ! array_key_exists( 'REMOTE_ADDR', $_SERVER )) {
            return false;
        }
        if ($this->decrypt( $_SESSION['ip'] ) === $_SERVER['REMOTE_ADDR']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check is user agent matches in requests
     *
     * @return bool
     */
    private function request_user_agent_matches()
    {

        if ( ! array_key_exists( 'user_agent', $_SESSION ) || ! array_key_exists( 'HTTP_USER_AGENT', $_SERVER )) {
            return false;
        }
        if ($this->decrypt( $_SESSION['user_agent'] ) === $_SERVER['HTTP_USER_AGENT']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check last login time
     * @return bool
     */
    private function last_login_check()
    {
        $max_elapsed = 60 * 60 * 24; // 1 day
        // return false if value is not set
        if ( ! array_key_exists( 'last_login', $_SESSION )) {
            return false;
        }
        $sesion_last_log = $this->decrypt( $_SESSION['last_login'] );
        /** @var $sesion_last_log int */
        if (( $sesion_last_log + $max_elapsed ) >= time()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks is session valid
     *
     * @return bool
     */
    private function is_session_valid()
    {
        if ( ! $this->request_ip_matches()) {
            return false;
        }
        if ( ! $this->request_user_agent_matches()) {
            return false;
        }
        if ( ! $this->last_login_check()) {
            return false;
        }

        return true;
    }

    /**
     * Check is session valid if not destroy session
     */
    private function confirm_session_valid()
    {
        if (array_key_exists( 'logged', $_SESSION ) && $this->decrypt(
                $_SESSION['logged']
            ) === true && ! $this->is_session_valid()
        ) {
            $this->session_end();
        }
    }

    public function is_logged() {
        return $this->getSessionKey('logged') === 'true';
    }
    /**
     * After log in perform regenerate_id
     *
     * @param integer $uid
     */
    public function set_after_login( $uid )
    {
        session_regenerate_id();

        /** @var $_SESSION array */
        $_SESSION['logged'] = $this->encrypt( 'true' );
        // Save these values in the session, even when checks aren't enabled
        $_SESSION['ip'] = $this->encrypt( $_SERVER['REMOTE_ADDR'] );
        $_SESSION['user_agent'] = $this->encrypt( $_SERVER['HTTP_USER_AGENT'] );
        $_SESSION['last_login'] = $this->encrypt( time() );
        $_SESSION['user_login'] = $this->encrypt( $uid );
    }

} 