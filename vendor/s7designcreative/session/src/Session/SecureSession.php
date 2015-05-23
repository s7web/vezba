<?php
namespace Session;

/**
 * Class SecureSession
 * @package Session
 *
 * @version 4-1-2015
 * @author  s7designcreative
 */
class SecureSession
{
    private $securekey, $iv;

    /**
     * Set up params
     */
    function __construct()
    {
        $textkey         = SECURE_KEY;
        $this->securekey = hash( 'sha256', $textkey, true );
        $this->iv        = mcrypt_create_iv( 32 );
    }

    /**
     * Encrypt given param
     *
     * @param $input
     *
     * @return string
     */
    function encrypt( $input )
    {
        return base64_encode(
            mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv )
        );
    }

    /**
     * Decrypt given param
     *
     * @param $input
     *
     * @return string
     */
    function decrypt( $input )
    {
        return trim(
            mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode( $input ), MCRYPT_MODE_ECB, $this->iv )
        );
    }

    /**
     * Set session key and encrypt it
     *
     * @param $name
     * @param $val
     */
    public function setSessionKey( $name, $val )
    {
        $_SESSION[$name] = $this->encrypt( $val );
    }

    /**
     * Get decrypted value from session
     *
     * @param $name
     *
     * @return string
     */
    public function getSessionKey( $name )
    {
        return $this->decrypt( $_SESSION[$name] );
    }
} 