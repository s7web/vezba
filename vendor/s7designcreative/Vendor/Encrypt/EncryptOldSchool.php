<?php
namespace S7D\Vendor\Encrypt;

/**
 * Class EncryptOldSchool
 * @package Encrypt
 *
 * @version 4-1-2015
 * @author  S7Designcreative
 */
class EncryptOldSchool implements EncryptInterface
{
    /** @var  string $this ->password */
    private $password;
    /** @var  string $this ->salt */
    private $salt;

    /**
     * Encrypt password
     *
     * @param $password
     *
     * @return string
     */
    public function make( $password )
    {
        $this->password = $this->clean( $password );
        $this->salt     = SECURE_KEY;

        $pass_enc = $this->encrypt();

        return $pass_enc;
    }

    /**
     * Ensure that we do htmlentities
     *
     * @param $password
     *
     * @return mixed
     */
    private function clean( $password )
    {

        return filter_var( $password, FILTER_SANITIZE_STRING );
    }

    /**
     * Returns encrypted password
     *
     * @return string
     */
    private function encrypt()
    {

        $pass_enc = sha1( md5( $this->salt ).sha1( $this->password ) );

        return $pass_enc;
    }
}