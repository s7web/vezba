<?php
namespace S7D\Core\Encrypt;

/**
 * Interface EncryptInterface
 * @package Encrypt
 *
 * @version 4-1-2015
 * @author  S7Designcreative
 */
interface EncryptInterface
{
    /**
     * Encrypt password
     *
     * @see EncryptBlowfish::make
     *
     * @param $password
     *
     * @return mixed
     */
    public function make( $password );

} 