<?php
/**
 * Created by PhpStorm.
 * User: nenadpaic
 * Date: 1/4/15
 * Time: 12:12 PM
 */

namespace Response;

/**
 * Class ResponseType
 * @package Response
 */
class ResponseType
{


    /**
     * Set header of response
     *
     * @param $status
     *
     * @return void
     *
     * @throws \Exception
     */
    private function setHeaderStatus( $status )
    {
        $status = (int) $status;
        if ( ! is_numeric( $status ) || $status > 600) {
            throw new \Exception( 'Invalid status' );
        }
        //header( ':', true, $status );
    }

    /**
     * Set content type of response
     *
     * @param string $content_type
     *
     * @return void
     */
    private function setContentType( $content_type )
    {

        header( $content_type );
    }


    /**
     * Set content type and headers and echo given json string
     *
     * @param array   $params
     * @param integer $status
     *
     * @return void
     *
     * @throws \Exception
     */
    public function response_json( $params, $status )
    {
        if ( ! is_array( $params )) {
            throw new \Exception( 'Invalid params for json response' );
        }

        $this->setContentType( 'Content-type: application/json' );
        $this->setHeaderStatus( $status );

        echo json_encode( $params );

        exit();
    }

    /**
     * Redirect app to given location
     *
     * @param string $location
     *
     * @return void
     *
     * @throws \Exception
     */
    public function redirect( $location )
    {
        ob_start();
        $this->setHeaderStatus( 301 );

        header( 'Location: /public/index.php/'.$location );
        ob_get_clean();
        exit();
    }

    /**
     * Redirect app to page from which request is coming
     *
     * @return void
     *
     * @throws \Exception
     */
    public function redirect_back()
    {
        ob_start();
        $location = $_SERVER['HTTP_REFERER'];
        $this->setHeaderStatus( 301 );
        header( 'Location: '.$location );
        ob_get_clean();
        exit();
    }

} 