<?php
namespace S7D\Core\Validator;

/**
 * Class ValidatorType
 * @package Validator
 *
 * @version 11.01.2015
 * @author S7Designcreative
 */
class ValidatorType
{

    private $errors = array();

    /**
     * Run validation check
     *
     * @param array $input
     * @param array $rules
     */
    public function __construct( array $input, array $rules )
    {

        foreach ($rules as $key => $value) {

            $rules = explode( '|', $value );

            foreach ($rules as $r) {

                if (strpos( $r, ':' )) {
                    $rules_param = explode( ':', $r );
                    $this->$rules_param[0]( $key, $input[$key], $rules_param[1] );
                    continue;
                }
                $this->$r( $key, $input[$key] );
            }
        }
    }

    /**
     * Check minimum string length
     *
     * @param string $key
     * @param string $value
     * @param string $length
     */
    private function min( $key, $value, $length )
    {
        $val_length = strlen( $value );
        if ($val_length < $length) {
            $this->setError( $key, "$key must be minimium $length characters long" );
        }
    }

    /**
     * Check max string length
     *
     * @param string $key
     * @param string $value
     * @param string $length
     */
    private function max( $key, $value, $length )
    {
        $val_length = strlen( $value );
        if ($val_length > $length) {
            $this->setError( $key, "$key must be less than $length characters long" );
        }
    }

    /**
     * If key is required check is filled
     *
     * @param string $key
     * @param string $value
     */
    private function required( $key, $value )
    {
        if ($value == '') {
            $this->setError( $key, "$key is required" );
        }
    }

    /**
     * Validate email
     *
     * @param string $key
     * @param string $value
     */
    private function email( $key, $value )
    {
        $email_validate = filter_var( $value, FILTER_VALIDATE_EMAIL );

        if ( ! $email_validate) {
            $this->setError( $key, "$key is not in valid format" );
        }
    }

    /**
     * Validate url
     *
     * @param string $key
     * @param string $value
     */
    private function url( $key, $value )
    {
        $valid_url = filter_var( $value, FILTER_VALIDATE_URL );
        if ( ! $valid_url) {
            $this->setError( $key, "That is not valid url" );
        }
    }

    /**
     * Checks is value in valid pattern. Allowed chars are alphanumeric with punctuation signs and spaces
     *
     * @param string $key
     * @param string $value
     */
    private function string_all( $key, $value )
    {
        if (preg_match( '/^[a-zA-Z0-9\,\.\!\'\"\?\_\-\:\; \-]+$/', $value ) === 0) {
            $this->setError(
                $key,
                "$key is not in valid format, its allowed only alpha numeric characters with punctuation and spaces!"
            );
        }
    }

    /**
     * Check is value valid. Only alphabet chars are allowed
     *
     * @param string $key
     * @param string $value
     */
    private function alpha( $key, $value )
    {
        if (preg_match( "/^[a-zA-Z]+$/", $value ) === 0) {
            $this->setError( $key, 'Only alphabet characters are allowed' );
        }
    }

    /**
     * Checks is value numeric
     *
     * @param string $key
     * @param string $value
     */
    private function numeric( $key, $value )
    {
        if ( ! is_numeric( $value )) {
            $this->setError( $key, "$key must be numeric" );
        }
    }

    /**
     * Checks is all submitted data valid
     *
     * @return bool
     */
    public function isValid()
    {
        if (empty( $this->errors )) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set validation error
     *
     * @param string $key
     * @param string $error
     */
    private function setError( $key, $error )
    {
        $this->errors[$key] = $error;
    }

    /**
     * Get all validation errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
} 