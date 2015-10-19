<?php
/**
 * Created by PhpStorm.
 * User: nenadpaic
 * Date: 1/4/15
 * Time: 12:03 PM
 */

namespace S7D\Core\Response;


/**
 * Class Response
 * @package Response
 */
class Response
{


    /**
     * Return JSON response
     *
     * Translate array to json string and return it, as well stop execution of code
     *
     * @param array   $params
     * @param integer $status
     *
     * @return void
     */
    public static function  json( $params, $status )
    {
        $response = new ResponseType();
        $response->response_json( $params, $status );

    }

    /**
     * Redirect back to page from which request comming
     *
     * @return void
     */
    public static function redirect_back()
    {

        $response = new ResponseType();
        $response->redirect_back();
    }

    /**
     * Redirect app to given location
     *
     * @param string $location
     *
     * @return void
     */
    public static function redirect( $location )
    {

        $response = new ResponseType();
        $response->redirect( $location );
    }
} 