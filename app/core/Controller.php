<?php

/**
 * Class Controller
 *
 * Class with base functions for Controllers
 *
 * @author  s7designcreative
 * @version 10-12-2014
 *
 *
 */
class Controller {

    /**
     * Instantiate model trough controller. if file is readable instantiate model if not return FALSE
     * @param $model
     * @return bool || object
     */
    protected function model( $model ) {

        if( is_readable( '../app/models/' . $model . '.php' ) ){
            require_once '../app/models/' . $model . '.php';

            return new $model();
        } else {
          return FALSE;
        }
    }

    /**
     * Calls a view file from controller
     * @param $view
     * @param array
     */
    protected function view( $view, $data )
    {
        $loader = new Twig_Loader_Filesystem( PATH_TO_SETUP . 'views' );
        $twig = new Twig_Environment( $loader );
        $twig->addExtension(new \Helpers\MenuExtension());
        $twig->addExtension(new \Helpers\LanguageExtension());

        $data[ 'view_data_config' ] = array(
            'site_name' => SITE_NAME,
            'site_url' => SITE_URL
        );

        echo $twig->render( $view, $data );

    }
}