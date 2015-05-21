<?php
/**
 * Created by PhpStorm.
 * User: nenadpaic
 * Date: 1/4/15
 * Time: 12:03 PM
 */

namespace Response;


class Response
{


    public static function  json( $params, $status )
    {
        $response = new ResponseType();
        $response->response_json( $params, $status );

    }

    public static function redirect_back(){

        $response = new ResponseType();
        $response->redirect_back();
    }

    public static function redirect( $location ){

        $response = new ResponseType();
        $response->redirect( $location );
    }
} 