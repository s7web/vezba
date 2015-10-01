<?php
namespace S7D\Vendor\Helpers;

/**
 * Class MenuExtension
 * @package Helpers
 *
 * @version 4-1-2015
 * @author  S7Designcreative
 */
class MenuExtension extends \Twig_Extension
{

    /**
     * Add new function to twig
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'sidebar_menu' => new \Twig_Function_Method( $this, 'generate_sidebar_menu' ),
            'menu'         => new \Twig_Function_Method( $this, 'generate_menu' ),
        );
    }

    /**
     * Function callback
     *
     * @param $class
     * @param $method
     */
    public function generate_sidebar_menu( $class, $method )
    {
        call_user_func( [ $class, $method ] );
    }

    /**
     * Function callback
     *
     * @param $class
     * @param $method
     */
    public function generate_menu( $class, $method )
    {
        require_once APP_PATH.'/controllers/'.strtolower( $class ).'.php';
        call_user_func( [ $class, $method ] );
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'menu';
    }
}