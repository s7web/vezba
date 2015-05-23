<?php
namespace Validator;

/**
 * Class Validator
 * @package Validator
 *
 * @version 11.01.2015
 * @author s7designcreative
 */
class Validator
{
    /**
     * Run the validator
     *
     * @param array $input
     * @param array $rules
     *
     * @return ValidatorType
     */
    public static function make( $input, $rules )
    {
        return new ValidatorType( $input, $rules );
    }
} 