<?php
namespace Encrypt;

/**
 * Class Encrypt
 * @package Encrypt
 *
 * @version 4-1-2015
 * @author  s7designcreative
 */
class Encrypt {

    public static function encrypt( $password, $method = 'oldschool' )
    {
        $pass_enc = new EncryptOldSchool();
        if (phpversion() >= 5.5 && $method === 'blowfish' ) {
            $pass_enc = new EncryptBlowfish();
        }
        if (phpversion() < 5.5 && $method === 'oldschool') {
            $pass_enc = new EncryptOldSchool();
        }

        if( ! $pass_enc instanceof EncryptInterface ){
            throw new \Exception('Wrong class implemented');
        }


        return $pass_enc->make( $password );

    }

} 