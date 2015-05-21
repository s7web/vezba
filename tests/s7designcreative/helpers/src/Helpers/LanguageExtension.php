<?php
namespace Helpers;


/**
 * Class LanguageExtension
 * @package Helpers
 *
 * @version 4-1-2015
 * @author  s7designcreative
 */
class LanguageExtension extends \Twig_Extension
{
    private $lang;

    /**
     * Set up params
     */
    public function __construct(){
        if(session_id() == '') {
            session_start();
        }
        $sec_session = DEFAULT_LANG;
        $lang = array();
        if( array_key_exists('lang', $_SESSION)){
            $sec_session =  $_SESSION['lang'];
        }
        if(is_readable(APP_PATH . '/languages/lang.' . $sec_session . '.php')){
            require_once APP_PATH . '/languages/lang.' . $sec_session . '.php';
            $this->lang = $lang;
        }else{
            throw new \Exception('Such language does not exist.');
        }

    }

    /**
     * Get twig filters
     * @return array
     */
    public function getFilters()
    {
        return array(
            'lang_trans' => new \Twig_SimpleFilter( 'lang_trans', array( $this, 'lang_trans' ) ),
        );
    }

    /**
     * Execute filter
     * @param $term
     * @return string
     */
    public function lang_trans( $term )
    {
        return sprintf('%s', $this->lang[ $term ] );
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'lang';
    }
} 