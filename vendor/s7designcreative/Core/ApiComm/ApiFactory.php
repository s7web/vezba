<?php

namespace S7D\Core\ApiComm;


/**
 * Class ApiFactory
 * @package ApiComm
 *
 * @author  Nenad Paic <npaic@S7Designcreative.com>
 * @version 01.06.2015
 */
class ApiFactory
{


    /**
     * Get Api for checking SEO rating
     *
     * Provide name of class that you need for API call, class must implement interface ApiInterface.
     * Currently only \Ahrefs\AhrefsAPI is available.
     *
     * @example $api = ApiComm\ApiFactory::getApi('\Ahrefs\AhrefsApi'); ( you will get instance of Ahrefs Api class )
     *
     * @param string $type
     *
     * @return $type class
     * @throws \Exception
     */
    public static function getApi( $type )
    {

        $className = __NAMESPACE__ . ucfirst( $type );

        if ( ! class_exists( $className )) {
            throw new \Exception( 'Missing format class' );
        }

        $class = new $className;

        if ( ! $class instanceof ApiInterface) {
            throw new \Exception( 'Class must implement ApiInterface' );
        }

        return new $className;
    }
}