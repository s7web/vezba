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

        //Form builder config in array format
        $config = $this->getFormConfig();

        $loader = new Twig_Loader_Filesystem(
            array(
                __DIR__.'/../../src/'.$annotations->module.'/views',
                __DIR__.'/../views',
                $config['vendorTwigBridge'].'/Resources/views/Form'
            )
        );

        $twig       = new Twig_Environment( $loader );
        $formEngine = new \Symfony\Bridge\Twig\Form\TwigRendererEngine( array( $config['defaultForm'] ) );
        $formEngine->setEnvironment( $twig );
        $twig->addExtension( new \Helpers\MenuExtension() );
        $twig->addExtension( new \Helpers\LanguageExtension() );
        $twig->addExtension(
            new FormExtension(
                new TwigRenderer( $formEngine )
            )
        );
        $twig->addExtension( new \Twig_Extension_Debug() );

        if (DEBUG_MODE) {
            $twig->enableDebug();
        }

        $data['view_data_config'] = array(
            'site_name' => SITE_NAME,
            'site_url'  => SITE_URL,
        );

        echo $twig->render( $view, $data );

    }

    /**
     * Get Symfony form builder
     *
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormBuilder()
    {

        $validator   = Validation::createValidator();
        $formFactory = Forms::createFormFactoryBuilder()
                            ->addExtension( new ValidatorExtension( $validator ) )
                            ->getFormFactory();

        return $formFactory;
    }

    /**
     * Get configuration for form builder
     *
     * @return array
     */
    private function getFormConfig()
    {

        $vendorDir = SITE_PATH.'vendor';
        $config    = array(
            'vendorFormDir'      => $vendorDir.'/symfony/form/Symfony/Component/Form',
            'vendorValidatorDir' => $vendorDir.'/symfony/validator/Symfony/Component/Validator',
            'vendorTwigBridge'   => $vendorDir.'/symfony/twig-bridge/Symfony/Bridge/Twig',
            'defaultForm'        => 'form_div_layout.html.twig',
        );

        return $config;
    }
}
