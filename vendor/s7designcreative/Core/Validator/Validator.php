<?php
namespace S7D\Core\Validator;

/**
 * Class Validator
 * @package Validator
 *
 * @version 11.01.2015
 * @author S7Designcreative
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