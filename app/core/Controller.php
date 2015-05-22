<?php

/**
 * Class Controller
 *
 * Class with base functions for Controllers
 *
 * @author  s7designcreative
 * @version 10-12-2014
 *
 */
class Controller {

    /**
     * Calls a view file from controller
     * @param $view
     * @param array
     */
    protected function view( $view, $data = array() )
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