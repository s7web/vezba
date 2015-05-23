<?php
namespace Encrypt;

/**
 * Interface EncryptInterface
 * @package Encrypt
 *
 * @version 4-1-2015
 * @author  s7designcreative
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