<?php

use Doctrine\Common\Annotations\AnnotationReader;
use \Symfony\Component\Form\Forms;
use \Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use \Symfony\Component\Validator\Validation;
use \Symfony\Bridge\Twig\Extension\FormExtension;
use \Symfony\Bridge\Twig\Form\TwigRenderer;

/**
 * Class Controller
 *
 * Class with base functions for Controllers
 *
 * @author  s7designcreative
 * @version 10-12-2014
 *
 */
class Controller
{

    private $message = '';
    private $messageClass = 'bg-info';

    /**
     * Calls a view file from controller
     *
     * @param $view
     * @param array
     *
     * @return void
     */
    protected function view( $view, $data = array() )
    {
        $className        = get_class( $this );
        $annotationReader = new AnnotationReader();
        $reflectionObject = new ReflectionClass( $className );
        $annotations      = $annotationReader->getClassAnnotation( $reflectionObject, 'Template' );

        $loader = new Twig_Loader_Filesystem(
            array(
                __DIR__.'/../../src/'.$annotations->module.'/views',
                __DIR__.'/../views',
            )
        );

        $twig       = new Twig_Environment( $loader );
        $twig->addExtension( new \Helpers\MenuExtension() );
        $twig->addExtension( new \Helpers\LanguageExtension() );
        $twig->addExtension( new \Twig_Extension_Debug() );

        if (DEBUG_MODE) {
            $twig->enableDebug();
        }

        $data['view_data_config'] = array(
            'site_name' => SITE_NAME,
            'site_url'  => SITE_URL,
        );

        $data['message'] = $this->message;
		if(isset($_SESSION['message'])){
			$data['message'] = $_SESSION['message'];
			unset($_SESSION['message']);
		}
        $data['messageClass'] = $this->messageClass;

        echo $twig->render( $view, $data );
    }

    protected function userMessage($message, $class = '')
    {
        $this->message       = $message;
		$_SESSION['message'] = $message;
        $this->messageClass = $class;
    }
}
