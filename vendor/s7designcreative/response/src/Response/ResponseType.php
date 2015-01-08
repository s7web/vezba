<?php
/**
 * Created by PhpStorm.
 * User: nenadpaic
 * Date: 1/4/15
 * Time: 12:12 PM
 */

namespace Response;


class ResponseType
{


    private function setHeaderStatus( $status )
    {
        $status = (int)$status;
        if (!is_numeric( $status ) || $status > 600) {
            throw new \Exception( 'Invalid status' );
        }
        header( ':', true, $status );
    }

    private function setContentType( $content_type )
    {

        header( $content_type );
    }


    public function response_json( $params, $status )
    {
        if (!is_array( $params )) {
            throw new \Exception( 'Invalid params for json response' );
        }

        $this->setContentType( 'Content-type: application/json' );
        $this->setHeaderStatus( $status );

        echo json_encode( $params );

        exit();
    }

    public function redirect( $location )
    {
        ob_start();
        $this->setHeaderStatus( 301 );

        header( 'Location: ' . $location );
        ob_get_clean();
        exit();
    }

    public function redirect_back()
    {
        ob_start();
        $location = $_SERVER[ 'HTTP_REFERER' ];
        $this->setHeaderStatus( 301 );
        header( 'Location: ' . $location );
        ob_get_clean();
        exit();
    }

} 