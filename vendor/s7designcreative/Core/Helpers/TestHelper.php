<?php

namespace S7D\Core\Helpers;

use Symfony\Component\Yaml\Parser;

/**
 * Class TestHelper
 * @package S7D\Core\Helpers
 */
class TestHelper extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $root;

    /**
     * Mock dependency injection container for tests
     *
     * @return Container
     */
    protected function mockContainer()
    {
        $this->container = new Container();

        $this->container->em         = function () {
            return $this->mockEntityManager();
        };
        $this->container->parameters = function () {
            return $this->mockParameter('parameters.yml');
        };
        $this->container->mailer     = function () {
            return $this->mockMailerClass();
        };
        $this->container->request    = function () {
            return $this->mockRequest();
        };
        $this->container->session    = function () {
            return $this->mockSession();
        };
        $this->container->root       = function () {
            return $this->mockRoot();
        };
        $this->container->user       = function () {
            return $this->mockUserClass();
        };

        $this->container->router = function () {
            return $this->mockRouter();
        };
        @session_start();
        $this->container->translations = function () {
            return $this->mockParameter('EN.yml', 'translations');
        };

        return $this->container;
    }

    /**
     * Mock entity manager doctrine
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockEntityManager()
    {

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager\EntityManager')
                   ->setMethods(array('getRepository', 'find', 'persist', 'flush'))
                   ->getMock();
        $em->method('getRepository')->will($this->returnSelf());
        $em->method('find')->will($this->returnValue(null));

        return $em;
    }

    /**
     * Mock Router class
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockRouter()
    {

        $router = $this->getMockBuilder('S7D\Core\Routing\Router')
                       ->setMethods(array())
                       ->getMock();

        return $router;
    }

    /**
     * Mock session class
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockSession()
    {

        $session = $this->getMockBuilder('\S7D\Core\HTTP\Session')
                        ->setMethods(array('getAuth'))
                        ->getMock();
        $session->method('getAuth')->will($this->returnValue(null));

        return $session;
    }

    /**
     * Mock request class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockRequest()
    {

        $request = $this->getMockBuilder('\S7D\Core\HTTP\Request')
                        ->setMethods(array('isPost'))
                        ->getMock();
        $request->method('isPost')->will($this->returnValue(false));

        return $request;
    }

    /**
     * Mock user class
     *
     * @return null
     */
    protected function mockUserClass()
    {
        return null;
    }

    /**
     * Mock mailer class
     *
     * @return null
     */
    protected function mockMailerClass()
    {
        return null;
    }

    /**
     * Mock root
     *
     * @return null
     */
    protected function mockRoot()
    {
        return '';
    }

    /**
     * Get various parameters from app and config
     *
     * @param string $filePattern
     * @param string $dir
     *
     * @return Parameter
     */
    protected function mockParameter($filePattern, $dir = 'config')
    {

        $yml = new Parser();

        $configDir = 'app/'.$dir.'/'.$filePattern;
        $data      = [];
        if (is_readable($configDir)) {
            $data = $yml->parse(file_get_contents($configDir));
        }

        $app = isset( $data['app'] ) ? $data['app'] : $this->container->parameters->get('app');

        $appConfig = 'src/S7D/App/'.$app.'/'.$dir.'/'.$filePattern;
        if (file_exists($appConfig)) {
            $test = $yml->parse(file_get_contents($appConfig));
            $data = array_merge($data, $test);
        }

        return new Parameter($data);
    }
}
