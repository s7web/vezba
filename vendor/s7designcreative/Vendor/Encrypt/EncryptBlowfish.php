<?php
namespace S7D\Vendor\Encrypt;

/**
 * Class EncryptBlowfish
 * @package Encrypt
 *
 * @version 4-1-2015
 * @author  S7Designcreative
 */
class EncryptBlowfish implements EncryptInterface
{
    /** @var  string $this ->password */
    private $password;
    /** @var  string $this ->salt */
    private $salt;
    /** @var  integer $this ->cost */
    private $cost;

    /**
     * Encrypt password using blowfish
     *
     * @param $password
     *
     * @return bool|string
     */
    public function make( $password )
    {
        $this->password = $this->clean_string( $password );
        $this->salt     = SECURE_KEY;
        $this->cost     = 12;

        $pass_encrypted = $this->encrypt();

        return $pass_encrypted;
    }

    /**
     * Ensure that we do htmlentities
     *
     * @param $password
     *
     * @return mixed
     */
    private function clean_string( $password )
    {
        return filter_var( $password, FILTER_SANITIZE_STRING );
    }

    /**
     * Returns encrypted password
     *
     * @return bool|string
     */
    private function encrypt()
    {
        $pass = password_hash( $this->password, PASSWORD_BCRYPT, [ 'cost' => $this->cost, 'salt' => $this->salt ] );

        return $pass;
    }
}